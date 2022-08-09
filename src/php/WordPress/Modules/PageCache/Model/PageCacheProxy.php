<?php

	namespace TutoMVC\WordPress\Modules\PageCache\Model;

	use TutoMVC\Utils\ArrayUtil;
	use TutoMVC\Utils\FileUtil;
	use TutoMVC\WordPress\Core\Model\Proxy\Proxy;
	use TutoMVC\WordPress\Modules\PageCache\PageCacheModule;

	class PageCacheProxy extends Proxy
	{
		const NAME = "page_cache_proxy";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		public function add( $html, $keyIgnored = NULL, $overrideIgnored = FALSE )
		{
			if ( is_file( PageCacheModule::getCurrentIndexHTML() ) ) unlink( PageCacheModule::getCurrentIndexHTML() );
			if ( is_file( PageCacheModule::getCurrentIndexHTMLGZ() ) ) unlink( PageCacheModule::getCurrentIndexHTMLGZ() );

			$dir = explode( DIRECTORY_SEPARATOR, PageCacheModule::getCurrentIndexHTML() );
			array_pop( $dir );
			$dir = implode( DIRECTORY_SEPARATOR, $dir );
			if ( !is_dir( $dir ) )
			{
				mkdir( $dir, 0777, TRUE );
			}
			file_put_contents( PageCacheModule::getCurrentIndexHTML(), $html );
			file_put_contents( PageCacheModule::getCurrentIndexHTMLGZ(), gzencode( $html ) );

			return TRUE;
		}

		public function listIndexes( $relativePath = NULL )
		{
			$map  = array();
			$root = PageCacheModule::formatPageCacheRoot( "/" );
			$path = PageCacheModule::formatPageCacheRoot( $relativePath );
			$pos  = strrpos( $path, $root ) + strlen( $root ) + 1;
			if ( is_dir( $path ) )
			{
				$di    = new \RecursiveDirectoryIterator( $path, \RecursiveDirectoryIterator::SKIP_DOTS );
				$it    = new \RecursiveIteratorIterator( $di );
				$regex = new \RegexIterator( $it, '/^.+\.html$/i', \RecursiveRegexIterator::GET_MATCH );

				foreach ( $regex as $file )
				{
					$filePath         = realpath( $file[ 0 ] );
					$relativePath     = substr( $filePath, $pos, - 11 );
					$map[ $filePath ] = $relativePath;
				}
			}

			usort( $map, array(ArrayUtil::class, "usortByDirectoryDepth") );

			return !empty( $map ) ? $map : NULL;
		}

		public function clearByURI( $uri, $recursively = FALSE )
		{
			$dir = PageCacheModule::formatPageCacheRoot( $uri );
			if ( $recursively )
			{
				return FileUtil::removeDirectoryRecursively( $dir );
			}
			else
			{
				$files = FileUtil::listFiles( $dir );
				foreach ( $files as $file )
				{
					unlink( $file );
				}
			}

			return TRUE;
		}

		public function delete( $key )
		{
			return unlink( $key );
		}

		public function clearAll()
		{
			return FileUtil::removeDirectoryRecursively( PageCacheModule::getPageCachePath() );
		}
	}
