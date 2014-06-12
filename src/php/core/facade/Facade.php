<?php
namespace tutomvc;
// https://github.com/PureMVC/puremvc-as3-multicore-framework/tree/master/src/org/puremvc/as3/multicore/patterns

class Facade
{
	/* PUBLIC CONSTANT VARS */
	const NAME = __CLASS__;
	const KEY_SYSTEM = "tutomvc/facade/system";

	/* PUBLIC VARS */
	public $vo;
	public $model;
	public $view;
	public $controller;

	/* PRIVATE VARS */
	private $_initialized = false;
	private $_key;
	private $_modulesMap = array();
	private static $_instanceMap = array();

	public function __construct( $key )
	{
		if( array_key_exists($key, self::$_instanceMap) ) throw new \ErrorException( "CUSTOM ERROR: "." Instance of Facade with that particular key already exists.", 0, E_ERROR );
		self::$_instanceMap[ $key ] = $this;
		$this->_key = $key;
		$this->initialize();
	}

	/* ACTIONS */
	private function initialize()
	{
		if($this->_initialized) return FALSE;
		$this->model = Model::getInstance( $this->getKey() );
		$this->view = View::getInstance( $this->getKey() );
		$this->controller = Controller::getInstance( $this->getKey() );
		$this->_initialized = TRUE;
		return $this->_initialized;
	}

	final public function registerSubFacade( $facade )
	{
		$this->_modulesMap[ $facade->getKey() ] = $facade;
		$facade->vo = $this->vo;
		$facade->onRegister();
		return $facade;
	}

	public function notify( $message, $type = Notification::TYPE_NOTICE )
	{
		$this->getSystem()->notificationCenter->add( $message, $type );

		return $this;
	}

	public function log( $message )
	{
		$this->getSystem()->logCenter->add( $message );

		return $this;
	}

	public function notifyAndLog( $message, $type = Notification::TYPE_NOTICE )
	{
		$this->notify( $message, $type );
		$this->log( $type . " - " . $message );

		return $this;
	}

	/* SET AND GET */
	public static function getInstance( $key )
	{
		if( array_key_exists( $key, self::$_instanceMap ) )
		{
			return self::$_instanceMap[ $key ];
		}
		else
		{
			return new Facade( $key );
		}
	}
	public function getSystem()
	{
		return Facade::getInstance( Facade::KEY_SYSTEM );
	}

	/**
	*	Get key.
	*/
	public function getKey()
	{
		return $this->_key;
	}
	/**
	*	Get APP URL.
	*/
	public function getURL( $relativePath = null )
	{
		return $this->vo->getURL( $relativePath );
	}

	/**
	*	Get template file path.
	*/
	public function getTemplateFileReference( $relativePath = null )
	{
		return $this->vo->getTemplateFileReference( $relativePath );
	}

	public function getVO()
	{
		return $this->vo;
	}

	/**
	*	Called when the facade is registered within Tuto Framework and ready.
	*/
	public function onRegister()
	{

	}
}