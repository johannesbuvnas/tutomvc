<?php
namespace tutomvc;

class ValueObject implements IValueObject
{
	/* VARS */
	private $_name;
	private $_value;


	function __construct( $name, $value )
	{
		$this->setName( $name );
		$this->setValue( $value );
	}

	/* SET AND GET */
	public function setValue( $value )
	{
		$this->_value = $value;
	}
	public function getValue()
	{
		return $this->_value;
	}
	function setName( $name )
	{
		$this->_name = $name;
	}
	function getName()
	{
		return $this->_name;
	}
}

interface IValueObject
{
	function setName( $name );
	function getName();
}