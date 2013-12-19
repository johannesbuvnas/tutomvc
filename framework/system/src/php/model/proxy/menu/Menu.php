<?php
namespace tutons;

class Menu extends ValueObject
{
	protected $_description;

	function __construct( $name, $description = "" )
	{
		$this->setName( $name );
		$this->setDescription( $description );
	}

	/* SET AND GET */
	public function setDescription( $description )
	{
		return $this->_description = $description;
	}
	public function getDescription()
	{
		return $this->_description;
	}
}