<?php
namespace tutomvc\modules\analytics;
use tutomvc\Facade;

class AnalyticsModule extends Facade
{
	const KEY = "tutomvc/modules/analytics/facade";

	const ACTION_RENDER = "tutomvc/modules/analytics/action/render";

	function __construct()
	{
		parent::__construct( self::KEY );
	}

	function onRegister()
	{
		$this->vo = $this->getSystem()->vo;
		$this->controller->registerCommand( new InitCommand() );
	}

	function render()
	{
		$this->view->getMediator( AnalyticsMediator::NAME )->render();
		$this->view->getMediator( GTMMediator::NAME )->render();
	}
}