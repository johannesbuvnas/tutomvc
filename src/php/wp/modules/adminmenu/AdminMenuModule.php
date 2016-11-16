<?php

	namespace tutomvc\wp\adminmenu;

	use tutomvc\wp\adminmenu\model\AdminMenuPage;
	use tutomvc\wp\adminmenu\model\AdminMenuPageProxy;
	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\system\SystemApp;

	class AdminMenuModule
	{
		public static function addPage( AdminMenuPage $adminMenuPage )
		{
			return self::getProxy()->add( $adminMenuPage, $adminMenuPage->getMenuSlug() );
		}

		/* SET AND GET */
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