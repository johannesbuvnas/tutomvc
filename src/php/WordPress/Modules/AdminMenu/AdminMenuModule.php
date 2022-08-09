<?php

	namespace TutoMVC\WordPress\Modules\AdminMenu;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\Modules\AdminMenu\Controller\AdminMenuPage;
	use TutoMVC\WordPress\Modules\AdminMenu\Model\AdminMenuPageProxy;
	use TutoMVC\WordPress\Modules\AdminMenu\Model\NetworkAdminMenuPageProxy;
	use TutoMVC\WordPress\System\SystemApp;

	class AdminMenuModule
	{
		public static function addPage( AdminMenuPage $adminMenuPage )
		{
			return self::getProxy()->add( $adminMenuPage, $adminMenuPage->isSubmenuPage() ? $adminMenuPage->getParentSlug() . "-" . $adminMenuPage->getMenuSlug() : $adminMenuPage->getMenuSlug() );
		}

		public static function addPageToNetwork( AdminMenuPage $adminMenuPage )
		{
			return self::getNetworkProxy()->add( $adminMenuPage, $adminMenuPage->isSubmenuPage() ? $adminMenuPage->getParentSlug() . "-" . $adminMenuPage->getMenuSlug() : $adminMenuPage->getMenuSlug() );
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
