<?php
	namespace tutomvc;

	class Controller
	{
		/* PROTECTED VARS */
		protected $_facadeKey;

		protected $_commandMap = array();

		/* STATIC VARS */
		protected static $_instanceMap = array();

		public function __construct( $key )
		{
			if ( array_key_exists( $key, $this::$_instanceMap ) ) die("ERROR! A Controller with that particular namespace already exists.");

			$this::$_instanceMap[ $key ] = $this;

			$this->_facadeKey = $key;
		}

		/* PUBLIC STATIC METHODS */
		public static function getInstance( $key )
		{
			if ( !array_key_exists( $key, self::$_instanceMap ) ) self::$_instanceMap[ $key ] = new Controller( $key );

			return self::$_instanceMap[ $key ];
		}

		/* PUBLIC METHODS */
		/**
		 * @param Command $command
		 *
		 * @return NULL|Command
		 */
		public function registerCommand( Command $command )
		{
			if ( $this->hasCommand( $command->getName() ) ) return $this->getCommand( $command->getName() );

			$command->initializeFacadeKey( $this->_facadeKey );
			$command->register();
			$this->_commandMap[ $command->getName() ] = $command;
			$command->onRegister();
		}

		/**
		 * @param string $commandName
		 *
		 * @return bool
		 */
		public function removeCommand( $commandName )
		{
			if ( $this->hasCommand( $commandName ) )
			{
				$this->getCommand( $commandName )->remove();
				unset($this->_commandMap[ $commandName ]);

				return TRUE;
			}

			return FALSE;
		}

		/**
		 * @param string $commandName
		 *
		 * @return Command|NULL
		 */
		public function getCommand( $commandName )
		{
			return $this->hasCommand( $commandName ) ? $this->getCommand( $commandName ) : NULL;
		}

		/**
		 * @param string $commandName
		 *
		 * @return bool
		 */
		public function hasCommand( $commandName )
		{
			return array_key_exists( $commandName, $this->_commandMap );
		}
	}
