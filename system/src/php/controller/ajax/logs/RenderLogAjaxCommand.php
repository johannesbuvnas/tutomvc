<?php
namespace tutomvc;

class RenderLogAjaxCommand extends AjaxCommand
{
	function __construct()
	{
		parent::__construct( AjaxCommands::RENDER_LOG );
	}

	function execute()
	{
		$this->getFacade()->view->getMediator( ExceptionMediator::NAME )
			->parse( "exception", new \ErrorException( $_REQUEST['title'], 0, 0, $_REQUEST['file'], -1 ) )
			->render();
		exit;
	}
}