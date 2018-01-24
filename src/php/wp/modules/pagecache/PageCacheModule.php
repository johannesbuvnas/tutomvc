<?php

	namespace tutomvc\wp\pagecache;

	use tutomvc\core\utils\FileUtil;
	use tutomvc\core\utils\URLUtil;
	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\system\SystemApp;

	class PageCacheModule
	{
		private static $_initialized             = FALSE;
		private static $_pageCachePath           = "";
		private static $_ignoreCacheMode         = TRUE;
		private static $_currentIndexHTML        = "";
		private static $_cacheTemplatePath       = "";
		private static $_originalTemplateInclude = "";
		private static $_expireTimeInSeconds     = 0;
		private static $_currentURL;

		/**
		 * @return PageCacheFacade
		 */
		public static function activate()
		{
			return self::getInstance();
		}

		public static function initialize()
		{
			if ( !self::$_initialized )
			{
				self::$_pageCachePath = FileUtil::sanitizePath( WP_CONTENT_DIR . "/cache/tutomvc/page/" );

				// .../wp-content/cache/**/*.html
				//////////////////////////////////////////
				self::$_currentIndexHTML = FileUtil::sanitizePath( self::formatCachePagePathByURL( URLUtil::formatCurrentURL() ) );
				self::$_currentIndexHTML .= "/index.html";
				//////////////////////////////////////////

				// Ignore cache?
				//////////////////////////////////////////
				self::$_ignoreCacheMode = self::hasLoggedInCookies() || !empty( $_POST );
				//////////////////////////////////////////

				self::$_initialized = TRUE;
			}
		}

		public static function hasLoggedInCookies()
		{
			$loggedInCookieKeys = array('wordpress_logged_in', 'comment_author_');
			foreach ( $_COOKIE as $key => $value )
			{
				foreach ( $loggedInCookieKeys as $loggedInCookieKey )
				{
					if ( strpos( $key, $loggedInCookieKey ) !== FALSE )
					{
						return TRUE;
					}
				}
			}

			return FALSE;
		}

		public static function formatCachePagePathByURL( $url )
		{
			$url = parse_url( $url );
			if ( $url === FALSE ) return NULL;
			$relativePath = $url[ 'host' ] . "/" . $url[ 'path' ];

			if ( array_key_exists( "query", $url ) && !empty( $url[ 'query' ] ) )
			{
				$relativePath = trim( $relativePath ) . "--" . preg_replace( "/[^A-Za-z0-9-]+/", '_', $url[ 'query' ] );
			}

			return self::formatPageCacheRoot( $relativePath );
		}

		/**
		 * @param null $relativePath
		 *
		 * @return mixed|string
		 */
		public static function formatPageCacheRoot( $relativePath = NULL )
		{
			return FileUtil::sanitizePath( self::$_pageCachePath . "/" . $relativePath );
		}

		public static function clearAll()
		{
			return self::getInstance()->getPageCacheProxy()->clearAll();
		}

		public static function renderPageCache()
		{
			if ( self::$_initialized )
			{
				$headers = array(
					"Content-type: text/html; charset=UTF-8",
					"Vary: Accept-Encoding, Cookie",
					"Cache-Control: max-age=3, must-revalidate"
				);
				switch ( self::isGZIPAccepted() )
				{
					case TRUE:

						$file = self::getCurrentIndexHTMLGZ();

						$headers[] = "Content-Encoding: gzip";

						break;
					case FALSE:

						$file = self::getCurrentIndexHTML();

						break;
				}

				if ( is_file( $file ) )
				{
					$output    = file_get_contents( $file );
					$size      = function_exists( 'mb_strlen' ) ? mb_strlen( $output, '8bit' ) : strlen( $output );
					$headers[] = 'Content-Length: ' . $size;
					if ( isset( $_SERVER[ 'HTTP_IF_MODIFIED_SINCE' ] ) ) $remote_mod_time = $_SERVER[ 'HTTP_IF_MODIFIED_SINCE' ];
					else $remote_mod_time = NULL;

					$local_mod_time = gmdate( "D, d M Y H:i:s", filemtime( $file ) ) . ' GMT';
					if ( !is_null( $remote_mod_time ) && $remote_mod_time == $local_mod_time )
					{
						header( "HTTP/1.0 304 Not Modified" );
						exit();
					}
					foreach ( $headers as $header )
					{
						header( $header );
					}
					echo $output;
					exit();
				}
			}
			else
			{
				throw new \Error( "Hasn't initialized." );
			}
		}

		public static function minifyHTML( $html )
		{
//			$search  = array(
//				'/\>[^\S ]+/s',
//				'/[^\S ]+\</s',
//				'/(\s)+/s'
//			);
//			$replace = array(
//				'>',
//				'<',
//				'\\1'
//			);
//			if ( preg_match( "/\<html/i", $html ) == 1 && preg_match( "/\<\/html\>/i", $html ) == 1 )
//			{
//				$html = preg_replace( $search, $replace, $html );
//			}

			return $html;
		}

		/**
		 * @return bool
		 */
		public static function isGZIPAccepted()
		{
			if ( 1 == ini_get( 'zlib.output_compression' ) || "on" == strtolower( ini_get( 'zlib.output_compression' ) ) ) // don't compress WP-Cache data files when PHP is already doing it
				return FALSE;

			if ( !isset( $_SERVER[ 'HTTP_ACCEPT_ENCODING' ] ) || (isset( $_SERVER[ 'HTTP_ACCEPT_ENCODING' ] ) && strpos( $_SERVER[ 'HTTP_ACCEPT_ENCODING' ], 'gzip' ) === FALSE) ) return FALSE;

			return TRUE;
		}

		/**
		 * WARNING! Formats a root path in the modules dir. Not facade root.
		 * Used internally to create admin stuff.
		 *
		 * @param null $relativePath
		 *
		 * @return mixed|string
		 */
		public static function getModuleRoot( $relativePath = NULL )
		{
			return is_null( $relativePath ) ? FileUtil::sanitizePath( dirname( __FILE__ ) ) : FileUtil::sanitizePath( dirname( __FILE__ ) . "/{$relativePath}" );
		}

		/**
		 * Has current indexed page file expired?
		 * @return bool
		 */
		public static function hasExpired()
		{
			$fileExpires = is_file( self::getCurrentIndexHTMLGZ() ) ? filemtime( self::getCurrentIndexHTMLGZ() ) + self::getExpireTimeInSeconds() : 1;

			return self::getExpireTimeInSeconds() > 0 && $fileExpires < time() ? TRUE : FALSE;
		}

		/**
		 * @return bool
		 */
		public static function isWPCacheEnabled()
		{
			return defined( "WP_CACHE" ) && WP_CACHE == TRUE;
		}

		/**
		 * @return bool
		 */
		public static function doesWPAdvancedCacheFileExists()
		{
			return is_file( WP_CONTENT_DIR . DIRECTORY_SEPARATOR . "advanced-cache.php" );
		}

		/**
		 * @return PageCacheFacade
		 */
		public static function getInstance()
		{
			return Facade::getInstance( PageCacheFacade::KEY ) ? Facade::getInstance( PageCacheFacade::KEY ) : SystemApp::getInstance()->registerModule( new PageCacheFacade() );
		}

		/**
		 * Ignore cache mode is set to true if you are logged in.
		 *
		 * @return bool
		 */
		public static function isIgnoreCacheMode()
		{
			return self::$_ignoreCacheMode;
		}

		/**
		 * @return string
		 */
		public static function getCurrentIndexHTML()
		{
			return self::$_currentIndexHTML;
		}

		/**
		 * @return string
		 */
		public static function getCurrentIndexHTMLGZ()
		{
			return self::getCurrentIndexHTML() . ".gz";
		}

		/**
		 * @return string
		 */
		public static function getCacheTemplatePath()
		{
			if ( empty( self::$_cacheTemplatePath ) ) self::$_cacheTemplatePath = self::getModuleRoot( "templates/view/page-cache-template.php" );

			return self::$_cacheTemplatePath;
		}

		/**
		 * @return string
		 */
		public static function getOriginalTemplateInclude()
		{
			return self::$_originalTemplateInclude;
		}

		/**
		 * @param string $originalTemplateInclude
		 */
		public static function setOriginalTemplateInclude( $originalTemplateInclude )
		{
			self::$_originalTemplateInclude = $originalTemplateInclude;
		}

		/**
		 * @return string
		 */
		public static function getPageCachePath()
		{
			return self::$_pageCachePath;
		}

		/**
		 * @return int
		 */
		public static function getExpireTimeInSeconds()
		{
			return self::$_expireTimeInSeconds;
		}

		public static function clearByURI( $uri, $recursively = FALSE )
		{
			return self::getInstance()->getPageCacheProxy()->clearByURI( $uri, $recursively );
		}

		/**
		 * @return mixed
		 */
		public static function getCurrentURL()
		{
			if ( empty( self::$_currentURL ) ) self::$_currentURL = URLUtil::formatCurrentURL();

			return self::$_currentURL;
		}
	}