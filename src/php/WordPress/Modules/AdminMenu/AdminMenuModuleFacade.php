<?php

	namespace TutoMVC\WordPress\Modules\AdminMenu;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\Modules\AdminMenu\Controller\Action\AdminMenuAction;
	use TutoMVC\WordPress\Modules\AdminMenu\Controller\Action\NetworkAdminMenuAction;
	use TutoMVC\WordPress\Modules\AdminMenu\Model\AdminMenuPageProxy;
	use TutoMVC\WordPress\Modules\AdminMenu\Model\NetworkAdminMenuPageProxy;

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
