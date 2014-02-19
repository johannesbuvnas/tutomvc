<?php
namespace tutomvc;
// https://github.com/PureMVC/puremvc-as3-multicore-framework/tree/master/src/org/puremvc/as3/multicore/patterns

// DEPENDENCIES
////////////////////////////////////////////////////////////////////////////////////////////////////////////
require_once realpath( dirname( __FILE__ ) ) . '/utils/ArrayUtil.php';
require_once realpath( dirname( __FILE__ ) ) . '/utils/FileUtil.php';
////////////////////////////////////////////////////////////////////////////////////////////////////////////

final class TutoMVC
{
	/* CONSTANTS */
	const VERSION = "1.01";
	const NONCE_NAME = "tutomvc/nonce";

	const SCRIPT_JS_REQUIRE = "require-js";
	const SCRIPT_JS_REQUIRE_PATH = "src/scripts/libs/requirejs/require.js";
	const SCRIPT_JS = "tutomvc-core-js";
	const SCRIPT_JS_PATH = "deploy/com.tutomvc.core.js";

	/* STATIC VARS */
	private static $developmentMode = true;

	private static $initiated = false;

	private static $_pluginRoot = "";
	private static $_src = "";

	private static $_rootURL = "";

	private static $facadeMap = array();

	private static $_priority = -1;

	/* PUBLIC OBJECTS */
	static $service;


	/* ACTIONS */
	/**
	*	Initializie.
	*/
	public static function initialize()
	{	
		if( self::$initiated ) 
		{
			return false;
		}

		$backtrace = debug_backtrace();
		$caller = $backtrace[0]['file'];
		self::$_pluginRoot = realpath( dirname( $caller ) );
		self::$_src = realpath( dirname( __FILE__ ) );

		$wpURL = get_bloginfo( 'wpurl' );
		$wpRoot = substr( $wpURL, strpos( $wpURL, $_SERVER['SERVER_NAME'] ) + strlen( $_SERVER['SERVER_NAME'] ) );
		self::$_rootURL = get_bloginfo( 'wpurl' ) . FileUtil::filterFileReference( substr( self::$_pluginRoot,  strpos( self::$_pluginRoot, $wpRoot ) + strlen( $wpRoot ) ) );

		FileUtil::import( self::$_src );

		// self::$service = new FrameworkService();
		
		self::$initiated = true;

		require_once( self::$_pluginRoot.'/system/bootstrap.php' );

		do_action( ActionCommand::START_UP );

		return true;
	}

	/**
	*	Import application into scope.
	*	@param string $facadeClassReference A reference to the class name which extends the Facade.
	*	@return boolean
	*/
	public static function startup( $facadeClassReference )
	{
		if(!self::$initiated)
		{
			die("ERROR! Cannot import application. Tuto Framework hasn't been initialized.");
		}

		if( self::hasImportedApplication( $facadeClassReference ) ) return false;

		$backtrace = debug_backtrace();
		$caller = $backtrace[0]['file'];
		$appRoot = realpath( dirname( $caller ) );

		FileUtil::import( $appRoot . "/src/php" );

		$facade = new $facadeClassReference();
		self::$facadeMap[ $facade->getKey() ] = new FacadeVO( $facadeClassReference, $facade->getKey(), $appRoot );
		if( $facade->getKey() != Facade::KEY_SYSTEM ) $facade->system = Facade::getInstance( Facade::KEY_SYSTEM );
		$facade->onRegister();
		do_action( ActionCommand::FACADE_READY, $facade->getKey() );

		return $facade;
	}

	/* SET AND GET */
	public static function getRoot()
	{
		return self::$_pluginRoot;
	}

	public static function getURL(  $relativePath = NULL )
	{
		return is_null( $relativePath ) ? self::$_rootURL : self::$_rootURL . FileUtil::filterFileReference( "/" . $relativePath );
	}
	
	/**
	*	@return FacadeVO
	*/
	public static function getApplicationVO( $facadeKey )
	{
		return self::$facadeMap[ $facadeKey ];
	}

	/**
	*	Returns initialize priority ID.
	*	@return int
	*/
	public static function getApplicationPriorityID()
	{
		return self::$_priority++;
	}

	/* PRIVATE METHODS */
	/**
	*	Checks if application has been imported.
	*	@return Boolean
	*/
	private static function hasImportedApplication( $facadeClassReference )
	{
		return class_exists( $facadeClassReference );
	}
}