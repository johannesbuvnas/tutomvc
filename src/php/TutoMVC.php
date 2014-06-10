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
	const VERSION = "1.029";
	const NAME = "tutomvc";
	const NONCE_NAME = "tutomvc/nonce";

	const SCRIPT_JS = "tutomvc-core-js";
	const SCRIPT_JS_PATH = "deploy/com.tutomvc.core.js";

	/* STATIC VARS */
	private static $initiated = FALSE;
	private static $_domain = "";
	private static $_wpRelativeRoot = "";
	private static $_documentRoot = "";
	private static $_root = "";
	private static $_src = "";
	private static $_url = "";
	private static $_facadeMap = array();
	private static $_priority = -1;

	/* ACTIONS */
	/**
	*	Initializie.
	*/
	public static function initialize()
	{
		if( self::$initiated )
		{
			return FALSE;
		}

		$backtrace = debug_backtrace();
		$caller = $backtrace[0]['file'];

		// Plugin root path
		self::$_root = realpath( dirname( $caller ) );
		// Plugin src path
		self::$_src = realpath( dirname( __FILE__ ) );
		// Figure out domain name
		$wpURL = get_bloginfo( 'wpurl' );
		self::$_domain = parse_url( $wpURL );
		self::$_domain = array_key_exists( "port", self::$_domain ) ? self::$_domain['host'] . ":" . self::$_domain['port'] : self::$_domain['host'];
		// Figure out the root of the WP folder
		self::$_wpRelativeRoot = substr( $wpURL, strpos( $wpURL, self::$_domain ) + strlen( self::$_domain ) );
		// Figure out URL to this plugin
		self::$_documentRoot = FileUtil::filterFileReference( getenv( "DOCUMENT_ROOT" ) );
		self::$_url = $wpURL . FileUtil::filterFileReference( substr( self::$_root,  strripos( self::$_root, self::$_documentRoot ) + strlen( self::$_wpRelativeRoot ) + strlen( self::$_documentRoot ) ) );

		self::requireAll( self::$_src );

		self::$initiated = true;

		// Auto load the system app facade
		require_once( self::$_root.'/system/bootstrap.php' );

		do_action( ActionCommand::START_UP );

		return TRUE;
	}

	/**
	*	Import application into scope.
	*	@param string $facadeClassReference A reference to the class name which extends the Facade.
	*	@return boolean
	*/
	public static function startup( $facadeClassReference, $templatesDir = "/templates/", $autoRequireAll = TRUE, $relativePath = "/src/php", $ignoredPaths = array() )
	{
		if(!self::$initiated)
		{
			die("ERROR! Cannot import application. Tuto Framework hasn't been initialized.");
		}

		// If this class already exists, don't do anything
		if( class_exists( $facadeClassReference ) ) return FALSE;

		// The file that called this function will set the root for this app
		$backtrace = debug_backtrace();
		$caller = $backtrace[0]['file'];
		$appRoot = realpath( dirname( $caller ) );

		// Require all PHP files from the app
		if($autoRequireAll)
		{
			self::requireAll( $appRoot . "/" . $relativePath, $ignoredPaths );
		}

		// Construct and initalize the facade
		$facade = new $facadeClassReference;
		self::$_facadeMap[ $facade->getKey() ] = $facade;
		$facade->vo = new FacadeVO( $appRoot );
		$facade->vo->templatesDir = $templatesDir;
		$facade->onRegister();
		do_action( ActionCommand::FACADE_READY, $facade->getKey() );
		return $facade;
	}

	/**
	*	Finds all PHP files and require them.
	*/
	public static function requireAll( $path, $ignoredPaths = array() )
	{
		return FileUtil::import( $path, $ignoredPaths );
	}

	/* SET AND GET */
	public static function getRoot()
	{
		return self::$_root;
	}
	public static function getDocumentRoot()
	{
		return self::$_documentRoot;
	}
	public static function getWPRelativeRoot()
	{
		return self::$_wpRelativeRoot;
	}

	public static function getURL(  $relativePath = NULL )
	{
		return is_null( $relativePath ) ? self::$_url : self::$_url . FileUtil::filterFileReference( "/" . $relativePath );
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
