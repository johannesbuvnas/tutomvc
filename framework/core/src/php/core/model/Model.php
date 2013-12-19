<?php
namespace tutons;

class Model
{
	/* PROTECTED VARS */
	protected $_facadeKey;

	protected $_proxyMap = array();

	protected static $_instanceMap = array();


	public function __construct( $key )
	{
		if( array_key_exists( $key, $this::$_instanceMap ) ) die( "ERROR! A Model with that particular namespace already exists." );

		$this::$_instanceMap[ $key ] = $this;

		$this->_facadeKey = $key;
	}
	
	/* PUBLIC STATIC METHODS */
	public static function getInstance( $key )
	{
		if( !array_key_exists($key, self::$_instanceMap) ) self::$_instanceMap[ $key ] = new Model( $key );
		
		return self::$_instanceMap[ $key ];
	}

	/* PUBLIC METHODS */
	public function registerProxy( $proxy )
	{
		$proxy->initializeFacadeKey( $this->_facadeKey );
		$this->_proxyMap[ $proxy->getName() ] = $proxy;
		$proxy->onRegister();

		return $proxy;
	}

	public function getProxy( $name )
	{
		return $this->hasProxy($name) ? $this->_proxyMap[$name] : NULL;
	}

	public function hasProxy( $name )
	{
		return isset ($this->_proxyMap[ $name ] );
	}
}