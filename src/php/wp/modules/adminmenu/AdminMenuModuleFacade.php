<?php

	namespace tutomvc\wp\adminmenu;

	use tutomvc\wp\adminmenu\controller\AdminMenuAction;
	use tutomvc\wp\adminmenu\controller\NetworkAdminMenuAction;
	use tutomvc\wp\adminmenu\model\AdminMenuPageProxy;
	use tutomvc\wp\adminmenu\model\NetworkAdminMenuPageProxy;
	use tutomvc\wp\core\facade\Facade;

	class AdminMenuModuleFacade extends Facade
	{
		const KEY = "com.tutomvc.wp.modules.adminmenu";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		function prepModel()
		{
			$this->registerProxy( new AdminMenuPageProxy() );
			$this->registerProxy( new NetworkAdminMenuPageProxy() );
		}

		function prepController()
		{
			$this->registerCommand( "admin_menu", new AdminMenuAction() );
			$this->registerCommand( "network_admin_menu", new NetworkAdminMenuAction() );
		}
	}