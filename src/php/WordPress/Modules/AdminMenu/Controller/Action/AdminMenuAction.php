<?php

	namespace TutoMVC\WordPress\Modules\AdminMenu\Controller\Action;

	use TutoMVC\WordPress\Core\Controller\Command\ActionCommand;
	use TutoMVC\WordPress\Modules\AdminMenu\AdminMenuModule;
	use TutoMVC\WordPress\Modules\AdminMenu\Controller\AdminMenuPage;

	class AdminMenuAction extends ActionCommand
	{
		function execute()
		{
			/** @var AdminMenuPage $adminMenuPage */
			foreach ( AdminMenuModule::getProxy()->getMap() as $adminMenuPage )
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
