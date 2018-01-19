<?php

	namespace tutomvc\wp\log;

	use tutomvc\wp\adminmenu\AdminMenuModule;
	use tutomvc\wp\adminmenu\model\AdminMenuPage;
	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\log\model\adminmenu\LogAdminMenuPage;

	class LogModuleFacade extends Facade
	{
		const KEY = "com.tutomvc.wp.modules.log";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		/* EVENTS */
		function onRegister()
		{
			// Model
			if ( is_admin() )
			{
				if ( is_network_admin() ) AdminMenuModule::addPageToNetwork( new LogAdminMenuPage( AdminMenuPage::PARENT_SLUG_NETWORK_SETTINGS ) );
				else AdminMenuModule::addPage( new LogAdminMenuPage( AdminMenuPage::PARENT_SLUG_SETTINGS ) );
			}

			$this->registerProxy( new LogProxy() );
			// Controller
		}
	}