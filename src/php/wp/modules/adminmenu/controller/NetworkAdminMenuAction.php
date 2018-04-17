<?php

	namespace tutomvc\wp\adminmenu\controller;

	use tutomvc\wp\adminmenu\AdminMenuModule;
	use tutomvc\wp\adminmenu\model\AdminMenuPage;
	use tutomvc\wp\core\controller\command\ActionCommand;

	class NetworkAdminMenuAction extends ActionCommand
	{
		function execute()
		{
			/** @var AdminMenuPage $adminMenuPage */
			foreach ( AdminMenuModule::getNetworkProxy()->getMap() as $adminMenuPage )
			{
				if ( !$adminMenuPage->isSubmenuPage() )
				{
					$id = add_menu_page( $adminMenuPage->getPageTitle(), $adminMenuPage->getMenuTitle(), $adminMenuPage->getCapability(), $adminMenuPage->getMenuSlug(), array(
						$adminMenuPage,
						"render"
					), $adminMenuPage->getIconURL(), $adminMenuPage->getPosition() );
				}
				else
				{
					$id = add_submenu_page( $adminMenuPage->getParentSlug(), $adminMenuPage->getPageTitle(), $adminMenuPage->getMenuTitle(), $adminMenuPage->getCapability(), $adminMenuPage->getMenuSlug(), array(
						$adminMenuPage,
						"render"
					) );
				}

				add_action( "load-$id", array($adminMenuPage, "load") );
			}
		}
	}