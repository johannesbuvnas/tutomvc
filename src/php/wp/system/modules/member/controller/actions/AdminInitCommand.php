<?php
namespace tutomvc\modules\privacy;
use \tutomvc\ActionCommand;

class AdminInitCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct("admin_init");
	}

	function execute()
	{
		$this->prepModel();
		$this->prepView();
		$this->prepController();
	}

	private function prepModel()
	{
	}

	private function prepView()
	{
	}

	private function prepController()
	{
		$this->getFacade()->controller->registerCommand( new RestrictWPAdminCommand() );

		do_action( RestrictWPAdminCommand::NAME );
	}
}