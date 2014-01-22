<?php
namespace tutomvc;

class AdminMenuPageProxy extends Proxy
{
	const NAME = __CLASS__;
	const WP_HOOK_REGISTER = "admin_menu";


	public function onRegister()
	{
		add_action( self::WP_HOOK_REGISTER, array( $this, "register" ) );

		// View
		$this->getFacade()->view->registerMediator( new AdminMenuSettingsPageMediator() );

		// Controller
		$this->getFacade()->controller->registerCommand( new RenderAdminMenuPageCommand() );
	}

	/* ACTIONS */
	public function add( AdminMenuPage $item )
	{
		$backtrace = debug_backtrace();
		$caller = $backtrace[0]['object'];
		$item->setFacadeKey( $caller->getFacade()->getKey() );
		return parent::add( $item, $item->getMenuSlug() );
	}

	/* ACTIONS */
	public function register()
	{
		foreach($this->getMap() as $item) $this->registerItem( $item );
	}

	protected function registerItem( AdminMenuPage $item )
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
	}

	/* EVENTS */
	public function renderItem()
	{
		do_action( ActionCommand::RENDER_ADMIN_MENU_PAGE );
	}
}