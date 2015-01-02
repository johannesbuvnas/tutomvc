<?php
	namespace tutomvc;

	class RenderMetaBoxProxyCommand extends ActionCommand
	{
		function __construct()
		{
			parent::__construct( ActionCommand::RENDER_META_BOX );
			$this->acceptedArguments = 2;
		}

		function execute()
		{
			$metaBoxName = $this->getArg( 0 );
			$metaBox     = $this->getSystem()->metaCenter->get( $metaBoxName );
			$postID      = !is_null( $this->getArg( 1 ) ) ? $this->getArg( 1 ) : 0;

			if ( $metaBox->filterValidateWPAdminOutput( $postID ) )
			{
				$mediator = $this->getMediator();
				$mediator->setMetaBox( $metaBox );
				$mediator->setPostID( $postID );
				$mediator->render();
			}
		}

		/**
		 * @return MetaBoxProxyMediator
		 */
		public function getMediator()
		{
			if ( !$this->getFacade()->view->hasMediator( MetaBoxProxyMediator::NAME ) ) return $this->getFacade()->view->registerMediator( new MetaBoxProxyMediator() );

			return $this->getFacade()->view->getMediator( MetaBoxProxyMediator::NAME );
		}
	}