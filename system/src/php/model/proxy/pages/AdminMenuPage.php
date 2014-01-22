<?php
namespace tutomvc;

class AdminMenuPage extends ValueObject
{
	/* CONSTANTS */
	const TYPE_NORMAL = "type_normal";
	const TYPE_THEME = "type_theme";
	const TYPE_OPTIONS = "type_options";

	/* VARS */
	protected $_pageTitle;
	protected $_menuTitle;
	protected $_capability;
	protected $_menuSlug;
	protected $_mediatorName;
	protected $_menuIconURL;
	protected $_menuPosition;
	protected $_facadeKey;
	protected $_type = self::TYPE_NORMAL;

	function __construct( $pageTitle, $menuTitle, $capability, $menuSlug, $mediatorName = NULL, $menuIconURL = NULL, $menuPosition = NULL )
	{
		$this->setPageTitle( $pageTitle );
		$this->setMenuTitle( $menuTitle );
		$this->setCapability( $capability );
		$this->setMenuSlug( $menuSlug );
		$this->setMediatorName( $mediatorName );
		$this->setMenuIconURL( $menuIconURL );
		$this->setMenuPosition( $menuPosition );
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

	public function setMediatorName( $value )
	{
		return $this->_mediatorName = $value;
	}
	public function getMediatorName()
	{
		return $this->_mediatorName;
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