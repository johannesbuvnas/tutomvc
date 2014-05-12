<?php
namespace tutomvc;

class SettingsField extends MetaField
{

	/* VARS */
	protected $_page;
	protected $_rendered = FALSE;
	protected $_autoload = TRUE;


	/* SET AND GET */
	public function setAutoload( $value )
	{
		$this->_autoload = $value;
		return $this;
	}
	public function getAutoload()
	{
		return $this->_autoload;
	}

	public function setPage( $value )
	{
		$this->_page = $value;
		return $this;
	}
	public function getPage()
	{
		return $this->_page;
	}

	public function setRendered( $value )
	{
		$this->_rendered = $value;
		return $this;
	}
	public function getRendered()
	{
		return $this->_rendered;
	}
}