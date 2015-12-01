<?php
	namespace tutomvc;

// https://github.com/PureMVC/puremvc-as3-multicore-framework/tree/master/src/org/puremvc/as3/multicore/patterns

// DEPENDENCIES
////////////////////////////////////////////////////////////////////////////////////////////////////////////
// require_once realpath( dirname( __FILE__ ) ) . '/utils/ArrayUtil.php';
// require_once realpath( dirname( __FILE__ ) ) . '/utils/FileUtil.php';
////////////////////////////////////////////////////////////////////////////////////////////////////////////

	final class TutoMVC
	{
		/* CONSTANTS */
		const VERSION     = "1.0.1";
		const NAME        = "tutomvc";
		const ACTION_INIT = "tutomvc_init";

		const SCRIPT_JS      = "tutomvc-core-js";
		const SCRIPT_JS_PATH = "deploy/com.tutomvc.core.js";

		/* STATIC VARS */
		private static $_initiated      = FALSE;
		private static $_domain         = "";
		private static $_wpRelativeRoot = "";
		private static $_documentRoot   = "";
		private static $_root           = "";
		private static $_url            = "";
		private static $_facadeMap      = array();

		/* ACTIONS */
		/**
		 *    Initializie.
		 */
		public static function initialize()
		{
			if ( !self::$_initiated )
			{
				$backtrace = debug_backtrace();
				$caller    = $backtrace[ 0 ][ 'file' ];

				// Plugin root path
				self::$_root = realpath( dirname( $caller ) );
				// Figure out domain name
				$wpURL         = get_bloginfo( 'wpurl' );
				self::$_domain = parse_url( $wpURL );
				self::$_domain = array_key_exists( "port", self::$_domain ) ? self::$_domain[ 'host' ] . ":" . self::$_domain[ 'port' ] : self::$_domain[ 'host' ];
				// Figure out the root of the WP folder
				self::$_wpRelativeRoot = substr( $wpURL, strpos( $wpURL, self::$_domain ) + strlen( self::$_domain ) );
				// Figure out URL to this plugin
				self::$_documentRoot = getenv( "DOCUMENT_ROOT" );
				self::$_url          = $wpURL . FileUtil::filterFileReference( substr( self::$_root, strripos( self::$_root, self::$_documentRoot ) + strlen( self::$_wpRelativeRoot ) + strlen( self::$_documentRoot ) ) );

				self::$_initiated = TRUE;

				// Auto load the system app facade
				global $systemFacade;
				$systemFacade = TutoMVC::startup( "\\tutomvc\\SystemAppFacade" );

				do_action( self::ACTION_INIT );
			}

			return self::$_initiated;
		}

		/**
		 *    Import application into scope.
		 *
		 * @param string $facadeClassReference A reference to the class name which extends the Facade.
		 *
		 * @return boolean
		 */
		public static function startup( $facadeClassReference )
		{
			if ( !self::$_initiated )
			{
				die("ERROR! Cannot import application. Tuto Framework hasn't been initialized.");
			}

			// The file that called this function will set the root for this app
			$backtrace = debug_backtrace();
			$caller    = $backtrace[ 0 ][ 'file' ];
			$appRoot   = realpath( dirname( $caller ) );
			$appURL    = get_bloginfo( 'wpurl' ) . FileUtil::filterFileReference( substr( $appRoot, strripos( $appRoot, TutoMVC::getDocumentRoot() ) + strlen( TutoMVC::getWPRelativeRoot() ) + strlen( TutoMVC::getDocumentRoot() ) ) );

			// Construct and initalize the facade
			/** @var Facade $facade */
			$facade = new $facadeClassReference;
			$facade->setRoot( $appRoot );
			$facade->setURL( $appURL );
			self::$_facadeMap[ $facade->getKey() ] = $facade;
			$facade->onRegister();
			do_action( ActionCommand::FACADE_READY, $facade );

			return $facade;
		}

		/* SET AND GET */
		public static function getRoot( $relativePath = NULL )
		{
			return is_null( $relativePath ) ? self::$_root : self::$_root . FileUtil::filterFileReference( "/" . $relativePath );
		}

		public static function getDocumentRoot( $relativePath = NULL )
		{
			return is_null( $relativePath ) ? self::$_documentRoot : self::$_documentRoot . FileUtil::filterFileReference( "/" . $relativePath );
		}

		public static function getWPRelativeRoot( $relativePath = NULL )
		{
			return is_null( $relativePath ) ? self::$_wpRelativeRoot : self::$_wpRelativeRoot . FileUtil::filterFileReference( "/" . $relativePath );
		}

		public static function getURL( $relativePath = NULL )
		{
			return is_null( $relativePath ) ? self::$_url : self::$_url . FileUtil::filterFileReference( "/" . $relativePath );
		}
	}
