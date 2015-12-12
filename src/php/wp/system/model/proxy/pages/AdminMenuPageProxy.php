<?php
namespace tutomvc\wp;

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
		$name =  call_user_func_array( "add_".$item->getType(), array( $item->getPageTitle(), $item->getMenuTitle(), $item->getCapability(), $item->getMenuSlug(), array( $this, "renderItem" ) ) );

		$item->setName( $name );
		add_action( "load-".$name, array( $item, "_onLoad" ) );

		foreach($item->getSubpages() as $adminMenuPage)
		{
			$name = add_submenu_page( $item->getMenuSlug(), $adminMenuPage->getPageTitle(), $adminMenuPage->getMenuTitle(), $adminMenuPage->getCapability(), $adminMenuPage->getMenuSlug(), array( $this, "renderItem" ) );
			$adminMenuPage->setName( $name );
			add_action( "load-".$name, array( $item, "_onLoad" ) );
		}
	}

	/* EVENTS */
	public function renderItem()
	{
		do_action( ActionCommand::RENDER_ADMIN_MENU_PAGE );
	}
}
