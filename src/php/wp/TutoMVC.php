<?php
	namespace tutomvc\wp;
	use tutomvc\core\utils\FileUtil;
	use tutomvc\wp\core\facade\Facade;

	final class TutoMVC
	{
		/* CONSTANTS */
		const NAME               = "tutomvc";
		const ACTION_INIT        = "tutomvc_init";
		const ACTION_FACADE_INIT = "tutomvc_facade_init";

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
		private static $_pkg;

		/* ACTIONS */
		/**
		 *    Initializie.
		 */
		public static function initialize()
		{
			if ( !self::$_initiated )
			{
				// Plugin root path
				self::$_root = realpath( dirname( __FILE__ ) . "/../../../" );

				// Figure out domain name
				$wpURL         = get_bloginfo( 'wpurl' );
				self::$_domain = parse_url( $wpURL );
				self::$_domain = array_key_exists( "port", self::$_domain ) ? self::$_domain[ 'host' ] . ":" . self::$_domain[ 'port' ] : self::$_domain[ 'host' ];

				// Figure out the root of the WP folder
				self::$_wpRelativeRoot = substr( $wpURL, strpos( $wpURL, self::$_domain ) + strlen( self::$_domain ) );

				// Figure out URL to this plugin
				self::$_documentRoot = str_replace( $_SERVER[ 'SCRIPT_NAME' ], '', $_SERVER[ 'SCRIPT_FILENAME' ] );
				self::$_url          = $wpURL . FileUtil::sanitizePath( substr( self::$_root, strripos( self::$_root, self::$_documentRoot ) + strlen( self::$_wpRelativeRoot ) + strlen( self::$_documentRoot ) ) );

				self::$_initiated = TRUE;

				// Auto load the system app facade
				global $systemFacade;
				$systemFacade = TutoMVC::startup( new SystemAppFacade() );

				do_action( self::ACTION_INIT );
			}

			return self::$_initiated;
		}

		/**
		 * @param Facade $facade
		 *
		 * @return Facade
		 */
		public static function startup( $facade )
		{
			if ( !self::$_initiated )
			{
				self::initialize();
			}

			// The file that called this function will set the root for this app
			$backtrace = debug_backtrace();
			$caller    = $backtrace[ 0 ][ 'file' ];
			$appRoot   = realpath( dirname( $caller ) );
			$appURL    = get_bloginfo( 'wpurl' ) . FileUtil::sanitizePath( substr( $appRoot, strripos( $appRoot, TutoMVC::getDocumentRoot() ) + strlen( TutoMVC::getWPRelativeRoot() ) + strlen( TutoMVC::getDocumentRoot() ) ) );

			$facade->setRoot( $appRoot );
			$facade->setURL( $appURL );
			self::$_facadeMap[ $facade->getKey() ] = $facade;
			$facade->onRegister();
			do_action( self::ACTION_FACADE_INIT, $facade );

			return $facade;
		}

		/* SET AND GET */
		public static function getRoot( $relativePath = NULL )
		{
			return is_null( $relativePath ) ? self::$_root : self::$_root . FileUtil::sanitizePath( "/" . $relativePath );
		}

		public static function getDocumentRoot( $relativePath = NULL )
		{
			return is_null( $relativePath ) ? self::$_documentRoot : self::$_documentRoot . FileUtil::sanitizePath( "/" . $relativePath );
		}

		public static function getWPRelativeRoot( $relativePath = NULL )
		{
			return is_null( $relativePath ) ? self::$_wpRelativeRoot : self::$_wpRelativeRoot . FileUtil::sanitizePath( "/" . $relativePath );
		}

		public static function getURL( $relativePath = NULL )
		{
			return is_null( $relativePath ) ? self::$_url : self::$_url . FileUtil::sanitizePath( "/" . $relativePath );
		}

		public static function getVersion()
		{
			return self::getPackageAsJSON( "version" );
		}

		public static function getPackageAsJSON( $param = NULL )
		{
			if ( empty(self::$_pkg) )
			{
				self::$_pkg = json_decode( file_get_contents( self::getRoot( "package.json" ) ) );
			}
			if ( is_string( $param ) )
			{
				if ( property_exists( self::$_pkg, $param ) ) return self::$_pkg->$param;

				return NULL;
			}

			return self::$_pkg;
		}
	}
