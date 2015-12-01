<?php
	namespace tutomvc;

// https://github.com/PureMVC/puremvc-as3-multicore-framework/tree/master/src/org/puremvc/as3/multicore/patterns
	/**
	 * Class Facade
	 * @package tutomvc
	 */
	class Facade
	{
		/* PRIVATE VARS */
		protected static $_instanceMap = array();
		protected        $_initialized = FALSE;
		protected        $_key;
		protected        $_modulesMap  = array();
		protected        $_root;
		protected        $_url;
		protected        $_model;
		public           $view;
		protected        $_controller;

		public function __construct( $key )
		{
			if ( array_key_exists( $key, self::$_instanceMap ) ) throw new \ErrorException( "CUSTOM ERROR: " . " Instance of Facade with that particular key already exists.", 0, E_ERROR );
			self::$_instanceMap[ $key ] = $this;
			$this->_key                 = $key;
			$this->initialize();
		}

		/* ACTIONS */
		protected function initialize()
		{
			if ( !$this->_initialized )
			{
				$this->_model       = Model::getInstance( $this->getKey() );
				$this->view         = View::getInstance( $this->getKey() );
				$this->_controller  = Controller::getInstance( $this->getKey() );
				$this->_initialized = TRUE;
			}

			return $this->_initialized;
		}

		/**
		 * @param Facade $facade
		 *
		 * @return mixed
		 */
		public function registerModule( $facade )
		{
			$this->_modulesMap[ $facade->getKey() ] = $facade;
			$facade->setRoot( $this->getRoot() );
			$facade->setURL( $this->getURL() );
			$facade->onRegister();

			return $facade;
		}

		/**
		 * @param $key
		 *
		 * @return Facade
		 */
		public static function getInstance( $key )
		{
			if ( array_key_exists( $key, self::$_instanceMap ) )
			{
				return self::$_instanceMap[ $key ];
			}
			else
			{
				return NULL;
			}
		}

		/* SET AND GET */
		/**
		 * @param string $commandName
		 * @param Command $command
		 * TODO: Move name to this function.
		 */
		public function registerCommand( $commandName, $command )
		{
			$this->getController()->registerCommand( $commandName, $command );
		}

		/**
		 * @param $commandName
		 *
		 * @return NULL|Command
		 */
		public function getCommand( $commandName )
		{
			return $this->getController()->getCommand( $commandName );
		}

		/**
		 * @param string $commandName
		 */
		public function removeCommand( $commandName )
		{
			$this->getController()->removeCommand( $commandName );
		}

		/**
		 * @param string $commandName
		 *
		 * @return bool
		 */
		public function hasCommand( $commandName )
		{
			return $this->getController()->hasCommand( $commandName );
		}

		/**
		 * @param Proxy $proxy
		 */
		public function registerProxy( $proxy )
		{
			$this->getModel()->registerProxy( $proxy );
		}

		/**
		 * @param $proxyName
		 *
		 * @return bool
		 */
		public function hasProxy( $proxyName )
		{
			return $this->getModel()->hasProxy( $proxyName );
		}

		/**
		 * @param $proxyName
		 *
		 * @return null|Proxy
		 */
		public function getProxy( $proxyName )
		{
			return $this->getModel()->getProxy( $proxyName );
		}

		/**
		 *    Get key.
		 */
		public function getKey()
		{
			return $this->_key;
		}

		/**
		 * @param string $url
		 */
		public function setURL( $url )
		{
			$this->_url = $url;
		}

		public function getURL( $relativePath = NULL )
		{
			return is_null( $relativePath ) ? $this->_url : $this->_url . FileUtil::filterFileReference( "/" . $relativePath );
		}

		/**
		 * @param string $root
		 */
		public function setRoot( $root )
		{
			$this->_root = $root;
		}

		public function getRoot( $relativePath = NULL )
		{
			return is_null( $relativePath ) ? FileUtil::filterFileReference( $this->_root ) : FileUtil::filterFileReference( $this->_root . "/{$relativePath}" );
		}

		/* EVENTS */
		/**
		 *    Called when the facade is registered within Tuto Framework and ready.
		 */
		public function onRegister()
		{

		}

		/**
		 * @return Model
		 */
		protected function getModel()
		{
			return $this->_model;
		}

		/**
		 * @return Controller
		 */
		protected function getController()
		{
			return $this->_controller;
		}

	}