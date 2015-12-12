<?php
namespace tutomvc\wp;

class PrintScriptsCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct( "admin_print_scripts" );
	}

	function execute()
	{
		$this->getFacade()->view->getMediator( JSGlobalMediator::NAME )->render();
	}
}