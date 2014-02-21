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
		if(wp_script_is( TutoMVC::NAME ))
		{
			$this->getFacade()->view->getMediator( JSGlobalMediator::NAME )->render();
		}
	}
}