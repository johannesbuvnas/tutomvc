<?php
namespace tutomvc\wp;

class AdminInitCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct( "admin_init" );
	}

	function execute()
	{
		$this->prepModel();
		$this->prepController();
	}

	private function prepModel()
	{
		foreach($this->getFacade()->settingsCenter->getMap() as $item) $this->getFacade()->settingsCenter->registerItem( $item );
	}

	private function prepController()
	{
		$this->getFacade()->controller->registerCommand( new AdminHeadCommand() );
		$this->getFacade()->controller->registerCommand( new AdminEnqueueScriptsCommand() );
		$this->getFacade()->controller->registerCommand( new AdminFooterCommand() );
		$this->getFacade()->controller->registerCommand( new PrepareMetaFieldCommand() );
	}
}
