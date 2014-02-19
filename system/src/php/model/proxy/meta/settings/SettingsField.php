<?php
namespace tutomvc;

class SettingsField extends MetaField
{

	/* VARS */
	protected $_page;
	protected $_rendered = FALSE;


	/* SET AND GET */
	public function setPage( $value )
	{
		$this->_page = $value;
	}
	public function getPage()
	{
		return $this->_page;
	}

	public function setRendered( $value )
	{
		$this->_rendered = $value;
	}
	public function getRendered()
	{
		return $this->_rendered;
	}
}