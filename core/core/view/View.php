<?php
namespace tutomvc;

class View
{
	/* PROTECTED VARS */
	protected $_facadeKey;

	protected $_mediatorMap = array();

	/* STATIC VARS */
	protected static $_instanceMap = array();


	public function __construct($key)
	{
		if( array_key_exists($key, $this::$_instanceMap) ) die( "ERROR! A View with that particular namespace already exists." );

		$this::$_instanceMap[ $key ] = $this;

		$this->_facadeKey = $key;
	}
	
	/* PUBLIC STATIC METHODS */
	public static function getInstance( $key )
	{
		if( !array_key_exists( $key, self::$_instanceMap ) ) self::$_instanceMap[$key] = new View( $key );
		
		return self::$_instanceMap[ $key ];
	}

	/* PUBLIC METHODS */
	public function registerMediator( Mediator $mediator )
	{
		if( $this->hasMediator( $mediator->getName() ) ) return FALSE;

		$mediator->initializeFacadeKey( $this->_facadeKey );
		$this->_mediatorMap[ $mediator->getName() ] = $mediator;
		$mediator->onRegister();

		return $mediator;
	}

	public function getMediator( $mediatorName )
	{
		return $this->hasMediator( $mediatorName ) ? $this->_mediatorMap[ $mediatorName ] : NULL;
	}

	public function hasMediator( $mediatorName )
	{
		return array_key_exists( $mediatorName, $this->_mediatorMap );
	}
}