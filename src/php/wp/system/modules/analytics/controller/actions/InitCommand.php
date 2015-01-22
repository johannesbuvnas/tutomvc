<?php
namespace tutomvc\modules\analytics;
use \tutomvc\ActionCommand;

class InitCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct("init");
	}

	function execute()
	{
		$this->prepModel();
		$this->prepView();
		$this->prepController();
	}

	private function prepModel()
	{
		// Admin menu pages
		$this->getSystem()->adminMenuPageCenter->add( new AnalyticsAdminMenuPage() );
		// Options
		$this->getSystem()->settingsCenter->add( new AnalyticsSettings() );
	}

	private function prepView()
	{
		$this->getFacade()->view->registerMediator( new AnalyticsMediator() );
		$this->getFacade()->view->registerMediator( new GTMMediator() );
	}

	private function prepController()
	{
		add_action( AnalyticsModule::ACTION_RENDER, array( $this->getFacade(), "render" ) );
	}
}