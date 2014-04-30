<?php
namespace tutomvc;

class TutoMVCSettingsPage extends AdminMenuPage
{
	const NAME = "tutomvc";


	function __construct( $mediator )
	{
		parent::__construct( __( "Tuto MVC" ), __( "Tuto MVC" ), "manage_options", self::NAME );
		//$this->setType( AdminMenuPage::TYPE_OPTIONS );
		$this->setMediator( $mediator );


		$this->addSubpage( new TutoMVCGitPage() )
		->setMediator( $mediator );

		$this->addSubpage( new TutoMVCLogsPage() )
			->setMediator( $mediator );
	}

	function getContentMediatorName()
	{
		return '';
<<<<<<< HEAD
	}

	function onLoad()
	{
		$screen = get_current_screen();
		if($screen->id == $this->getName())
		{
			$facade = Facade::getInstance( Facade::KEY_SYSTEM );
			$facade->notificationCenter->add( "Tuto MVC " . TutoMVC::VERSION );
		}
=======
>>>>>>> affebb7cb75c5d18df94e0c38db3bf735f0c58e0
	}
}
