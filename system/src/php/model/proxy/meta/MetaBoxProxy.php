<?php
namespace tutomvc;

class MetaBoxProxy extends Proxy
{
	const NAME = __CLASS__;

	public function onRegister()
	{
		/* ACTIONS */
		$this->getFacade()->controller->registerCommand( new AddMetaBoxCommand() );
		$this->getFacade()->controller->registerCommand( new RenderMetaBoxProxyCommand() );

		/* AJAX */
		$this->getFacade()->controller->registerCommand( new RenderMetaBoxAjaxCommand() );
		$this->getFacade()->controller->registerCommand( new RenderWPEditorAjaxCommand() );
		
		/* FILTERS */
		$this->getFacade()->controller->registerCommand( new GetMetaValueFilterCommand() );
		$this->getFacade()->controller->registerCommand( new GetMetaDatFilter() );
	}

	public function add( $item, $key = NULL )
	{
		foreach($item->getSupportedPostTypes() as $postType)
		{
			if(!$this->getFacade()->controller->hasCommand( SavePostCommand::constructHookName( $postType ) )) $this->getFacade()->controller->registerCommand( new SavePostCommand( $postType ) );
		}

		parent::add( $item, $item->getName() );
	}
}