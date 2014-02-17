<?php
namespace tutomvc;

class PrintScriptsCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct( "wp_print_scripts" );
	}

	function execute()
	{
		if(wp_script_is( TutoMVC::SCRIPT_JS, "registered" ))
		{
			$this->getFacade()->view->getMediator( JSGlobalMediator::NAME )->render();
		}
	}
}