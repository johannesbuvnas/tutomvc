<?php
	namespace tutomvc\wp;

	class LogProxy extends Proxy
	{

		/* METHODS */
		public function add( $message, $key = NULL )
		{
			$file = $this->getLogFile();

			if ( !is_file( $file ) ) {
				$newFileContent = "<?php defined('ABSPATH') or die('No direct script access.'); ?>";
				$this->createFile( $file, $newFileContent );
			}

			$fileContent = file_get_contents( $file );
			$fileContent .= "\n" . date( "Y-m-d H:i:s e" ) . " ::: " . $message;

			return file_put_contents( $file, $fileContent );
		}

		protected function createFile( $file, $content )
		{
			$dir      = explode( '/', $file );
			$fileName = array_pop( $dir );
			$dir      = implode( '/', $dir );
			if ( !is_dir( $dir ) ) {
				mkdir( $dir, 0777, TRUE );
			}

			return file_put_contents( "$dir/$fileName", $content );
		}

		/* SET AND GET */
		public function get( $key )
		{
			return NULL;
		}

		public function getMap()
		{
			if ( !count( $this->_map ) && is_dir( self::getLogsPath() ) ) {
				$di    = new \RecursiveDirectoryIterator( self::getLogsPath(), \RecursiveDirectoryIterator::SKIP_DOTS );
				$it    = new \RecursiveIteratorIterator( $di );
				$regex = new \RegexIterator( $it, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH );

				foreach ( $regex as $file ) {
					$fileSplit                                   = explode( "/", FileUtil::filterFileReference( $file[ 0 ] ) );
					$d                                           = basename( array_pop( $fileSplit ), ".php" );
					$m                                           = array_pop( $fileSplit );
					$y                                           = array_pop( $fileSplit );
					$this->_map[ strtotime( "{$y}-{$m}-{$d}" ) ] = $file[ 0 ];
				}

				ksort( $this->_map, SORT_NUMERIC );
				$newMap = array();
				foreach ( $this->_map as $key => $value ) {
					$newMap[ date( "Y-m-d", $key ) ] = $value;
				}
				$this->_map = array_reverse( $newMap );
			}

			return $this->_map;
		}

		public function getLogFile( $time = 0 )
		{
			if ( !$time ) {
				$time = time();
			}

			$year  = date( "Y", $time );
			$month = date( "m", $time );
			$day   = date( "d", $time );

			return self::getLogsPath( $year . "/" . $month . "/" . $day . ".php" );
		}

		public static function getLogsPath( $realtivePath = NULL )
		{
			$realtivePath = $realtivePath ? "logs/" . $realtivePath : "logs";

			return Facade::getInstance( Facade::KEY_SYSTEM )->getVO()->getRoot( $realtivePath );
		}

	}