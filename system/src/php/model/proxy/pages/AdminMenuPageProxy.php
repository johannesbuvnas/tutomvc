<?php
namespace tutomvc;

class AdminMenuPageProxy extends Proxy
{
	const NAME = __CLASS__;


	public function onRegister()
	{
		// View
		$this->getFacade()->view->registerMediator( new AdminMenuSettingsPageMediator() );

		// Controller
		$this->getFacade()->controller->registerCommand( new RenderAdminMenuPageCommand() );

		$this->add( new TutoMVCSettingsPage( $this->getFacade()->view->registerMediator( new TutoMVCSettingsPageMediator() ) ) );
	}

	/* ACTIONS */
	public function add( $item, $key = NULL )
	{
		if(get_class($item) == "tutomvc\AdminMenuSettingsPage" || is_subclass_of($item, "tutomvc\AdminMenuSettingsPage"))
		{
			if(is_null($item->getMediator())) $item->setMediator( $this->getFacade()->view->getMediator( AdminMenuSettingsPageMediator::NAME ) );
		}

		return parent::add( $item, $item->getMenuSlug() );
	}

	public function find( $name )
	{
		foreach($this->getMap() as $adminMenuPage)
		{
			if($adminMenuPage->getName() == $name) return $adminMenuPage;

			foreach($adminMenuPage->getSubpages() as $adminMenuSubpage)
			{
				if($adminMenuSubpage->getName() == $name) return $adminMenuSubpage;
			}
		}

		return NULL;
	}

	/* ACTIONS */
	public function registerItem( AdminMenuPage $item )
	{
		switch( $item->getType() )
		{
			case AdminMenuPage::TYPE_THEME:

				$name = add_theme_page( $item->getPageTitle(), $item->getMenuTitle(), $item->getCapability(), $item->getMenuSlug(), array( $this, "renderItem" ) );

			break;
			case AdminMenuPage::TYPE_OPTIONS:

				$name = add_options_page( $item->getPageTitle(), $item->getMenuTitle(), $item->getCapability(), $item->getMenuSlug(), array( $this, "renderItem" ) );

			break;
			default:

				$name = add_menu_page( $item->getPageTitle(), $item->getMenuTitle(), $item->getCapability(), $item->getMenuSlug(), array( $this, "renderItem" ), $item->getMenuIconURL(), $item->getMenuPosition() );

			break;
		}
		
		$item->setName( $name );

		foreach($item->getSubpages() as $adminMenuPage)
		{
			$adminMenuPage->setName( add_submenu_page( $item->getMenuSlug(), $adminMenuPage->getPageTitle(), $adminMenuPage->getMenuTitle(), $adminMenuPage->getCapability(), $adminMenuPage->getMenuSlug(), array( $this, "renderItem" ) ) );
		}
	}

	/* EVENTS */
	public function renderItem()
	{
		do_action( ActionCommand::RENDER_ADMIN_MENU_PAGE );
	}
}