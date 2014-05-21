<?php
namespace tutomvc;

class RenderUserMetaBoxCommand extends ActionCommand
{
	const NAME = "show_user_profile";

	function __construct()
	{
		parent::__construct( self::NAME );

		$this->setExecutionLimit( 1 );
	}

	public function register()
	{
		parent::register();
		// For non current user profiles
		add_action( "edit_user_profile", array( $this, "preExecution" ), $this->priority, $this->acceptedArguments );
	}

	function execute()
	{
		do_action( ActionCommand::PREPARE_META_FIELD );
		
		$user = $this->getArg(0);
		
		foreach($this->getFacade()->userMetaCenter->getMap() as $userMetaBox)
		{
			$mediator =  $this->getMediator();
			$mediator->setMetaBox( $userMetaBox );
			$mediator->setPostID( $user->ID );
			?>
			<h3><?php echo $userMetaBox->getTitle(); ?></h3>
			<div class="UserMeta">
				<?php
				$mediator->render();
			?>
			</div>
			<?php
		}
	}

	public function getMediator()
	{
		if(!$this->getFacade()->view->hasMediator( MetaBoxProxyMediator::NAME )) return $this->getFacade()->view->registerMediator( new MetaBoxProxyMediator() );

		return $this->getFacade()->view->getMediator( MetaBoxProxyMediator::NAME );
	}
}