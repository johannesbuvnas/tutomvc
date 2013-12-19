<?php
namespace tutons;

class RenderMetaBoxProxyCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct( ActionCommand::RENDER_META_BOX );
		$this->acceptedArguments = 2;
	}

	function execute( $metaBoxName, $postID = 0 )
	{
		$mediator = $this->getMediator();
		$mediator->setMetaBox( $this->getFacade()->model->getProxy( MetaBoxProxy::NAME )->get( $metaBoxName ) );
		$mediator->setPostID( $postID );
		$mediator->render();
	}

	public function getMediator()
	{
		if(!$this->getFacade()->view->hasMediator( MetaBoxProxyMediator::NAME )) return $this->getFacade()->view->registerMediator( new MetaBoxProxyMediator() );

		return $this->getFacade()->view->getMediator( MetaBoxProxyMediator::NAME );
	}
}