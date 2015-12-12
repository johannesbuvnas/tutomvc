<?php
namespace tutomvc\wp;

class AdminMenuCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct( "admin_menu" );
	}

	function execute()
	{	
		$this->prepModel();
	}

	private function prepModel()
	{
		foreach($this->getFacade()->adminMenuPageCenter->getMap() as $item) $this->getFacade()->adminMenuPageCenter->registerItem( $item );
	}
}