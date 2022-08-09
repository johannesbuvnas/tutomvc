<?php

	namespace TutoMVC\WordPress\Core\Model\Cache;

	use TutoMVC\Form\FormElement;
	use TutoMVC\WordPress\Core\Facade\Facade;

	class LocalStorageCacheDriver implements ICacheDriver
	{
		const PATH_ROOT_DIR_NAME = "tutomvc_cache";

		protected $_facadeKey;

		/**
		 * @param string $key
		 * @param $group
		 *
		 * @return string
		 */
		public function formatPath( $group, $key = NULL )
		{
			$relativePaths   = array(WP_CONTENT_DIR);
			$relativePaths[] = "cache";
			$relativePaths[] = "tutomvc";
			$relativePaths[] = "object";
			if ( empty( $group ) ) $group = "default";
			$relativePaths[] = strtolower( FormElement::sanitizeID( $group ) );
			if ( !empty( $key ) ) $relativePaths[] = strtolower( FormElement::sanitizeID( $key ) ) . ".php";

			$relativePath = implode( DIRECTORY_SEPARATOR, $relativePaths );

			return $relativePath;
		}

		public function formatCache( $key, $data, $expire )
		{
			$data = serialize( $data );

			return '
			<?php 
				defined("ABSPATH") or die("No direct script access.");
				$cachedData = array(
					"key" => "' . base64_encode( $key ) . '",
					"expire" => "' . $expire . '",
					"data" => "' . base64_encode( $data ) . '"
				);
				
				return $cachedData;
			?>
			';
		}

		public function readCacheFromFile( $filePath )
		{
			if ( is_file( $filePath ) )
			{
				$cachedData = include "$filePath";
				if ( !empty( $cachedData ) && is_array( $cachedData ) )
				{
					if ( !strlen( $cachedData[ 'data' ] ) ) return NULL;
					$cachedData[ 'key' ]  = base64_decode( $cachedData[ 'key' ] );
					$cachedData[ 'data' ] = base64_decode( $cachedData[ 'data' ] );
					$cachedData[ 'data' ] = unserialize( $cachedData[ 'data' ] );

					return $cachedData;
				}
			}

			return NULL;
		}

		/**
		 * @param string|int $key
		 * @param mixed $data
		 * @param string $group
		 * @param int $expire
		 *
		 * @return boolean
		 */
		public function set( $key, $data, $group = '', $expire = 0 )
		{
			$filePath = $this->formatPath( $group, $key );
			if ( is_file( $filePath ) ) unlink( $filePath );
			$dir      = explode( '/', $filePath );
			$fileName = array_pop( $dir );
			$dir      = implode( '/', $dir );
			if ( !is_dir( $dir ) )
			{
				mkdir( $dir, 0777, TRUE );
			}
			$fileContent = $this->formatCache( $key, $data, $expire > 0 ? time() + $expire : 0 );
			$result      = file_put_contents( $filePath, $fileContent );

			return TRUE;
		}

		/**
		 * @param string|int $key
		 * @param string $group
		 *
		 * @return mixed
		 */
		public function get( $key, $group = '' )
		{
			$cache = $this->readCacheFromFile( $this->formatPath( $group, $key ) );

			if ( empty( $cache ) ) return NULL;

			$expire  = intval( $cache[ 'expire' ] );
			$expired = FALSE;
			if ( $expire < time() && $expire > 0 ) $expired = TRUE;

			return $expired ? NULL : $cache[ 'data' ];
		}

		/**
		 * @param string $group
		 *
		 * @return mixed
		 */
		public function getGroup( $group = '' )
		{
			$map  = array();
			$path = $this->formatPath( $group );
			if ( is_dir( $path ) )
			{
				$di    = new \RecursiveDirectoryIterator( $path, \RecursiveDirectoryIterator::SKIP_DOTS );
				$it    = new \RecursiveIteratorIterator( $di );
				$regex = new \RegexIterator( $it, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH );

				foreach ( $regex as $file )
				{
					$filePath = realpath( $file[ 0 ] );
					$cache    = $this->readCacheFromFile( $filePath );
					if ( is_array( $cache ) ) $map[ $cache[ 'key' ] ] = $cache[ 'data' ];
				}
			}

			return empty( $map ) ? NULL : $map;
		}

		/**
		 * @param string|int $key
		 * @param string $group
		 *
		 * @return boolean
		 */
		public function delete( $key, $group = '' )
		{
			$filePath = $this->formatPath( $group, $key );
			if ( is_file( $filePath ) )
			{
				return unlink( $filePath );
			}

			return FALSE;
		}

		/**
		 * @param string $group
		 *
		 * @return boolean
		 */
		public function deleteGroup( $group = '' )
		{
			$dir = $this->formatPath( $group );
			if ( is_dir( $dir ) )
			{
				$di    = new \RecursiveDirectoryIterator( $dir, \RecursiveDirectoryIterator::SKIP_DOTS );
				$it    = new \RecursiveIteratorIterator( $di );
				$regex = new \RegexIterator( $it, '/^.+\.php$/i', \RecursiveRegexIterator::GET_MATCH );

				foreach ( $regex as $file )
				{
					$filePath = realpath( $file[ 0 ] );
					unlink( $filePath );
				}

				return rmdir( $dir );
			}

			return FALSE;
		}

		/**
		 * @param string $facadeKey
		 *
		 */
		public function setFacadeKey( $facadeKey )
		{
			$this->_facadeKey = $facadeKey;
		}

		/**
		 *
		 * @return string
		 */
		public function getFacadeKey()
		{
			return $this->_facadeKey;
		}

		/**
		 * @return Facade
		 */
		public function getFacade()
		{
			return Facade::getInstance( $this->getFacadeKey() );
		}
	}
