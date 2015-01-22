<?php
namespace tutomvc\modules\privacy;
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
		$this->getSystem()->adminMenuPageCenter->add( new PrivacySettingsAdminMenuPage() );
		// Options
		$this->getSystem()->settingsCenter->add( new PrivacySettings() );
		// Meta
		$this->getSystem()->metaCenter->add( new PrivacyMetaBox() );
	}

	private function prepView()
	{
	}

	private function prepController()
	{
		$this->getFacade()->controller->registerCommand( new AdminInitCommand() );
		$this->getFacade()->controller->registerCommand( new ShowAdminBarFilterCommand() );
		$this->getFacade()->controller->registerCommand( new WPCommand() );

		if(!is_admin())
		{
			$this->getFacade()->controller->registerCommand( new PreGetPostsAction() );
		}
	}
}