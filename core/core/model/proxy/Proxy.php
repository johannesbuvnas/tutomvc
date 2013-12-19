<?php
namespace tutons;

class Proxy extends CoreClass
{
	/* PROTECTED VARS */
	protected $_map = array();

	protected $_name;

	function __construct( $name = NULL )
	{
		$this->_name = is_null( $name ) ? get_class( $this ) : $name;
	}

	public function add( $item, $key = NULL )
	{
		if( is_null( $key ) ) $this->_map[] = $item;
		else $this->_map[ $key ] = $item;
	}

	public function has( $key )
	{
		return isset( $this->_map[ $key ] );
	}

	public function get( $key )
	{
		return $this->has( $key ) ? $this->_map[ $key ] : NULL;
	}

	public function getMap()
	{
		return $this->_map;
	}

	public function getName()
	{
		return $this->_name;
	}
}