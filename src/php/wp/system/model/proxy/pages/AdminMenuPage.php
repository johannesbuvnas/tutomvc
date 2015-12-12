<?php
namespace tutomvc\wp;

class AdminMenuPage extends ValueObject
{
	/* CONSTANTS */
	const TYPE_NORMAL = "menu_page";
	const TYPE_THEME = "theme_page";
	const TYPE_OPTIONS = "options_page";
	const TYPE_COMMENTS = "comments_page";
	const TYPE_PAGES = "pages_page";
	const TYPE_PLUGINS = "plugins_page";
	const TYPE_USERS = "users_page";
	const TYPE_MANAGEMENT = "management_page";
	const TYPE_LINKS = "links_page";
	const TYPE_MEDIA = "media_page";
	const TYPE_POSTS = "posts_page";
	const TYPE_DASHBOARD = "dashboard_page";

	/* VARS */
	protected $_pageTitle;
	protected $_menuTitle;
	protected $_capability;
	protected $_menuSlug;
	protected $_mediator;
	protected $_menuIconURL;
	protected $_menuPosition;
	protected $_type = self::TYPE_NORMAL;
	protected $_subpages = array();

	function __construct( $pageTitle, $menuTitle, $capability, $menuSlug, $menuIconURL = NULL, $menuPosition = NULL )
	{
		$this->setPageTitle( $pageTitle );
		$this->setMenuTitle( $menuTitle );
		$this->setCapability( $capability );
		$this->setMenuSlug( $menuSlug );
		$this->setMenuIconURL( $menuIconURL );
		$this->setMenuPosition( $menuPosition );
	}

	/* ACTIONS */
	public function addSubpage( AdminMenuPage $page )
	{
		$this->_subpages[ $page->getMenuSlug() ] = $page;

		return $page;
	}

	/* SET AND GET */
	public function setPageTitle( $value )
	{
		return $this->_pageTitle = $value;
	}
	public function getPageTitle()
	{
		return $this->_pageTitle;
	}

	public function setMenuTitle( $value )
	{
		return $this->_menuTitle = $value;
	}
	public function getMenuTitle()
	{
		return $this->_menuTitle;
	}

	public function setCapability( $value )
	{
		return $this->_capability = $value;
	}
	public function getCapability()
	{
		return $this->_capability;
	}

	public function setMenuSlug( $value )
	{
		return $this->_menuSlug = $value;
	}
	public function getMenuSlug()
	{
		return $this->_menuSlug;
	}

	public function setMediator( AdminMenuPageMediator $value )
	{
		$this->_mediator = $value;

		return $this;
	}
	public function getMediator()
	{
		return $this->_mediator;
	}

	public function setMenuIconURL( $value )
	{
		return $this->_menuIconURL = $value;
	}
	public function getMenuIconURL()
	{
		return $this->_menuIconURL;
	}

	public function setMenuPosition( $value )
	{
		return $this->_menuPosition = $value;
	}
	public function getMenuPosition()
	{
		return $this->_menuPosition;
	}

	public function setType( $value )
	{
		$this->_type = $value;
	}
	public function getType()
	{
		return $this->_type;
	}

	public function getSubpage( $menuSlug )
	{
		return array_key_exists( $menuSlug, $this->_subpages ) ? $this->_subpages[ $menuSlug ] : NULL;
	}
	public function getSubpages()
	{
		return $this->_subpages;
	}

	/* EVENTS */
	/** Triggered by WordPress */
	public function onLoad()
	{

	}
	public function _onLoad()
	{
		$this->onLoad();

		$screen = get_current_screen();
		foreach($this->getSubpages() as $subpage)
		{
			if($subpage->getName() == $screen->id) $subpage->onLoad();
		}
	}

	/**
	*	Used by AdminMenuPageProxy
	*/
	public function setFacadeKey( $value )
	{
		$this->_facadeKey = $value;
	}
	public function getFacadeKey()
	{
		return $this->_facadeKey;
	}
}
