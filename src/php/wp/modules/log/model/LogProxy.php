<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 19/11/15
	 * Time: 08:25
	 */

	namespace tutomvc\wp\log;

	use function get_current_blog_id;
	use function is_multisite;
	use tutomvc\core\model\ValueObject;
	use tutomvc\wp\core\model\proxy\Proxy;

	class LogProxy extends Proxy
	{
		const NAME          = __CLASS__;
		const ROOT_DIR_NAME = "tutomvc_logs";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		/**
		 * @param ValueObject $item
		 * @param null $key
		 * @param bool|FALSE $override
		 *
		 * @return int
		 */
		public function add( $item, $key = NULL, $override = FALSE )
		{
			$logFilePath = $this->getLogFileByTimestamp();

			if ( !is_file( $logFilePath ) )
			{
				$this->createLogFile( $logFilePath );
			}

			$logMessage = "\n" . date( "Y-m-d H:i:s e" ) . " ::: " . $item->getValue();

			return file_put_contents( $logFilePath, $logMessage, FILE_APPEND | LOCK_EX );
		}

		public function delete( $time = 0 )
		{
			$logFilePath = $this->getLogFileByTimestamp( $time );

			if ( is_file( $logFilePath ) )
			{
				unlink( $logFilePath );

				return TRUE;
			}

			return FALSE;
		}

		protected function createLogFile( $file )
		{
			$content  = "<?php defined('ABSPATH') or die('No direct script access.'); ?>";
			$dir      = explode( '/', $file );
			$fileName = array_pop( $dir );
			$dir      = implode( '/', $dir );
			if ( !is_dir( $dir ) )
			{
				mkdir( $dir, 0777, TRUE );
			}

			return file_put_contents( "$dir/$fileName", $content );
		}

		/* SET AND GET */
		function getMap()
		{
			if ( !count( $this->_map ) && is_dir( $this->getLogPath() ) )
			{
				$di    = new \RecursiveDirectoryIterator( $this->getLogPath(), \RecursiveDirectoryIterator::SKIP_DOTS );
				$it    = new \RecursiveIteratorIterator( $di );
				$regex = new \RegexIterator( $it, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH );

				foreach ( $regex as $file )
				{
					$filePath                                    = realpath( $file[ 0 ] );
					$fileSplit                                   = explode( DIRECTORY_SEPARATOR, $filePath );
					$d                                           = basename( array_pop( $fileSplit ), ".php" );
					$m                                           = array_pop( $fileSplit );
					$y                                           = array_pop( $fileSplit );
					$this->_map[ strtotime( "{$y}-{$m}-{$d}" ) ] = $filePath;
				}

				ksort( $this->_map, SORT_NUMERIC );
				$newMap = array();
				foreach ( $this->_map as $key => $value )
				{
					$newMap[ date( "Y-m-d", $key ) ] = $value;
				}
				$this->_map = array_reverse( $newMap );
			}

			return parent::getMap();
		}

		public function getLogFileByTimestamp( $time = 0 )
		{
			if ( empty( $time ) )
			{
				$time = time();
			}

			$year  = date( "Y", $time );
			$month = date( "m", $time );
			$day   = date( "d", $time );

			return $this->getLogFilePath( $year, $month, $day );
		}

		public function getLogFilePath( $year, $month, $day )
		{
			$relativePath = $year . "/" . $month . "/" . $day . ".php";
			if ( is_multisite() )
			{
				$blogID       = get_current_blog_id();
				$relativePath = "site-" . $blogID . "/" . $relativePath;
			}

			return $this->getLogPath( $relativePath );
		}

		public function getLogPath( $relativePath = NULL )
		{
			$relativePath = $relativePath ? self::ROOT_DIR_NAME . "/" . $relativePath : self::ROOT_DIR_NAME;

			return get_stylesheet_directory() . "/" . $relativePath;
		}

		/* EVENTS */
	}