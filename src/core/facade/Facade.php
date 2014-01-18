<?php
namespace tutomvc;
// https://github.com/PureMVC/puremvc-as3-multicore-framework/tree/master/src/org/puremvc/as3/multicore/patterns

class Facade
{
	/* PUBLIC CONSTANT VARS */
	const NAME = __NAMESPACE__;

	const KEY_SYSTEM = "tutomvc/facade/system";

	/* PUBLIC STATIC VARS */

	/* PUBLIC VARS */
	public $noticeModel;

	public $system;

	/* PROTECTED VARS */
	private $_initialized = false;

	protected $_key;

	public $model;

	public $view;

	public $controller;

	/* STATIC VARS */
	protected static $_instanceMap = array();


	public function __construct( $key )
	{
		if( array_key_exists($key, $this::$_instanceMap) ) die( "Instance of Facade with that particular key already exists." );

		$this::$_instanceMap[ $key ] = $this;

		$this->_key = $key;

		$this->initializeFacade();
	}

	private function initializeFacade()
	{
		if($this->_initialized) return false;

		$this->model = Model::getInstance( $this->getKey() );

		$this->view = View::getInstance( $this->getKey() );

		$this->controller = Controller::getInstance( $this->getKey() );

		$this->_initialized = true;

		return true;
	}

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

	/* PUBLIC METHODS */
	/**
	*	Called when the facade is registered within Tuto Framework and ready.
	*/
	public function onRegister()
	{

	}

	/**
	*	Get multiton key.
	*/
	public function getKey()
	{
		return $this->_key;
	}

	/* Publish a admin notice. */
	public function notify($message, $type = Notice::TYPE_NORMAL)
	{
		if (!session_id())
		{
			$this->noticeModel->add( new Notice($message, $type) );
		}
		else 
		{
			if($_SESSION[ Cookies::ADMIN_NOTICES ]) array_push( $_SESSION[ Cookies::ADMIN_NOTICES ], new Notice($message, $type) );
			else $_SESSION[ Cookies::ADMIN_NOTICES ] = array( 0 => new Notice($message, $type) );
		}
	}

	public function getURL( $relativePath = null )
	{
		return TutoFramework::getApplicationVO( $this->_key )->getURL( $relativePath );
	}

	public function getTemplateFileReference( $relativePath = null )
	{
		return TutoFramework::getApplicationVO( $this->_key )->getTemplateFileReference( $relativePath );
	}

	public function getVO()
	{
		return TutoFramework::getApplicationVO( $this->_key );
	}

	/**
	*	Publish a debug message.
	*/
	public function debug($message)
	{
		if(TutoFramework::$debugMode)
		{
			$this->renderView("\\".__NAMESPACE__."\DebugView", array(
					"message" => $message
				));
		}
	}
}