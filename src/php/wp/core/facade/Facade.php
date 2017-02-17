<?php
	namespace tutomvc\wp\core\facade;

	use tutomvc\wp\core\controller\command\Command;
	use tutomvc\wp\core\controller\Controller;
	use tutomvc\wp\core\model\cache\ICacheDriver;
	use tutomvc\wp\core\model\Model;
	use tutomvc\wp\core\model\proxy\Proxy;
	use tutomvc\wp\core\view\View;
	use tutomvc\wp\utils\FileUtil;

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
		protected        $_view;
		protected        $_controller;
		protected        $_cacheDriver;

		public function __construct( $key )
		{
			if ( array_key_exists( $key, self::$_instanceMap ) ) throw new \ErrorException( "Instance of Facade with that particular key already exists.", 0, E_ERROR );
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
				$this->_view        = View::getInstance( $this->getKey() );
				$this->_controller  = Controller::getInstance( $this->getKey() );
				$this->_initialized = TRUE;
			}

			return $this->_initialized;
		}

		/**
		 * Override.
		 */
		protected function prepView()
		{
		}

		/**
		 * Override.
		 */
		protected function prepModel()
		{
		}

		/**
		 * Override.
		 */
		protected function prepController()
		{
		}

		/**
		 * Modules will inherit the root, cache driver from it's parent.
		 *
		 * @param Facade $facade
		 *
		 * @return mixed
		 */
		public function registerModule( $facade )
		{
			$this->_modulesMap[ $facade->getKey() ] = $facade;
			$facade->setRoot( $this->getRoot() );
			$facade->setURL( $this->getURL() );
			if ( $this->getCacheDriver() ) $facade->setCacheDriver( $this->getCacheDriver() );
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

		/**
		 * @param string $relativePath File path. Relative to facade's root.
		 * @param null|string $name Optional name of view component. {$relativePath}-{$name}.php
		 * @param array $dataProvider Data to send to view component.
		 * @param bool|FALSE $returnOutput
		 *
		 * @return bool|string
		 * @throws \ErrorException
		 */
		public function render( $relativePath, $name = NULL, $dataProvider = array(), $returnOutput = FALSE )
		{
			return $this->getView()->render( $relativePath, $name, $dataProvider, $returnOutput );
		}

		public function isViewComponent( $relativePath, $name = NULL )
		{
			return $this->getViewComponentRealpath( $relativePath, $name ) != NULL;
		}

		public function getViewComponentRealpath( $relativePath, $name = NULL )
		{
			return $this->getView()->getViewComponentRealpath( $relativePath, $name );
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
			$this->prepModel();
			$this->prepView();
			$this->prepController();
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

		/**
		 * @return View
		 */
		protected function getView()
		{
			return $this->_view;
		}

		/**
		 * @return ICacheDriver
		 */
		final function getCacheDriver()
		{
			return $this->_cacheDriver;
		}

		/**
		 * @param ICacheDriver $cacheDriver
		 */
		final function setCacheDriver( $cacheDriver )
		{
			$cacheDriver->setFacadeKey( $this->getKey() );
			$this->_cacheDriver = $cacheDriver;
		}

	}