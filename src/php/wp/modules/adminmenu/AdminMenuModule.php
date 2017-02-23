<?php

	namespace tutomvc\wp\adminmenu;

	use tutomvc\wp\adminmenu\model\AdminMenuPage;
	use tutomvc\wp\adminmenu\model\AdminMenuPageProxy;
	use tutomvc\wp\adminmenu\model\NetworkAdminMenuPageProxy;
	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\system\SystemApp;

	class AdminMenuModule
	{
		public static function addPage( AdminMenuPage $adminMenuPage )
		{
			return self::getProxy()->add( $adminMenuPage, $adminMenuPage->getMenuSlug() );
		}

		public static function addPageToNetwork( AdminMenuPage $adminMenuPage )
		{
			return self::getNetworkProxy()->add( $adminMenuPage, $adminMenuPage->getMenuSlug() );
		}

		/* SET AND GET */
		/**
		 * @return NetworkAdminMenuPageProxy
		 */
		public static function getNetworkProxy()
		{
			return self::getInstance()->getProxy( NetworkAdminMenuPageProxy::NAME );
		}

		/**
		 * @return AdminMenuPageProxy
		 */
		public static function getProxy()
		{
			return self::getInstance()->getProxy( AdminMenuPageProxy::NAME );
		}

		/**
		 * @return AdminMenuModuleFacade
		 */
		public static function getInstance()
		{
			return Facade::getInstance( AdminMenuModuleFacade::KEY ) ? Facade::getInstance( AdminMenuModuleFacade::KEY ) : SystemApp::getInstance()->registerModule( new AdminMenuModuleFacade() );
		}
	}