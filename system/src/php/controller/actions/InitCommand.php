<?php
namespace tutomvc;

class InitCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct( "init" );
	}

	function execute()
	{
		wp_register_script( TutoMVC::SCRIPT_JS_REQUIRE, TutoMVC::getURL( TutoMVC::SCRIPT_JS_REQUIRE_PATH ), TutoMVC::VERSION );
		wp_register_script( TutoMVC::SCRIPT_JS, TutoMVC::getURL( TutoMVC::SCRIPT_JS_PATH ), array( TutoMVC::SCRIPT_JS_REQUIRE ), TutoMVC::VERSION );
		wp_register_script( SystemFacade::SCRIPT_JS_MAIN, $this->getFacade()->getURL( SystemPaths::SCRIPT_JS_MAIN ), array( TutoMVC::SCRIPT_JS ), TutoMVC::VERSION, TRUE );

		$this->getFacade()->controller->registerCommand( new PrintScriptsCommand() );
	}
}