<?php
namespace tutomvc;

class Controller
{
	/* PROTECTED VARS */
	protected $_facadeKey;

	protected $_commandMap = array();

	/* STATIC VARS */
	protected static $_instanceMap = array();


	public function __construct($key)
	{
		if( array_key_exists($key, $this::$_instanceMap) ) die( "ERROR! A Controller with that particular namespace already exists." );

		$this::$_instanceMap[ $key ] = $this;

		$this->_facadeKey = $key;
	}

	/* PUBLIC STATIC METHODS */
	public static function getInstance( $key )
	{
		if( !array_key_exists( $key, self::$_instanceMap ) ) self::$_instanceMap[$key] = new Controller( $key );

		return self::$_instanceMap[ $key ];
	}

	/* PUBLIC METHODS */
	public function registerCommand( Command $command )
	{
		if( $this->hasCommand( $command->getName() ) ) return $this->getCommand( $command->getName() );

		$command->initializeFacadeKey( $this->_facadeKey );
		$command->register();
		$this->_commandMap[ $command->getName() ] = $command;
		$command->onRegister();

		return $command;
	}
	public function removeCommand( $name )
	{
		if($this->hasCommand($name))
		{
			$this->getCommand($name)->remove();
			unset( $this->_commandMap[ $name ] );

			return TRUE;
		}

		return FALSE;
	}

	public function getCommand( $commandName )
	{
		return $this->_commandMap[ $commandName ];
	}

	public function hasCommand( $commandName )
	{
		return array_key_exists( $commandName, $this->_commandMap );
	}
}
