<?php
namespace tutomvc;

class TutoMVCLogsPageContentMediator extends Mediator
{
	const NAME = "menu/settings/tutomvc/content/logs.php";

	function __construct()
	{
		parent::__construct( self::NAME );
	}

	function onRegister()
	{
		$this->getFacade()->controller->registerCommand( new RenderLogAjaxCommand() );
	}
}