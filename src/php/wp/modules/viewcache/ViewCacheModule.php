<?php

	namespace tutomvc\wp\viewcache;

	use tutomvc\wp\core\facade\Facade;

	class ViewCacheModule
	{
		/**
		 * @param Facade $parentFacade
		 *
		 * @param int $cacheExpirationTimeInSeconds
		 *
		 * @throws \ErrorException
		 */
		public static function activate( $parentFacade, $cacheExpirationTimeInSeconds = 0 )
		{
			if ( self::getInstance() ) throw new \ErrorException( "ViewCacheModule has already been activated!" );

			$parentFacade->registerModule( new ViewCacheFacade( $cacheExpirationTimeInSeconds ) );
		}

		/**
		 * Renders a file. If it exists in the cache, it will render the cached version.
		 *
		 * @param $relativePath
		 * @param null $name
		 * @param array $dataProvider
		 * @param bool $returnOutput
		 *
		 * @return bool|mixed|null|string
		 */
		public static function render( $relativePath, $name = NULL, $dataProvider = array(), $returnOutput = FALSE )
		{
			return self::getInstance()->render( $relativePath, $name, $dataProvider, $returnOutput );
		}

		public static function outputIgnoreViewCacheHTMLComment()
		{
			echo ViewCacheFacade::HTML_COMMENT_IGNORE_VIEW_CACHE_KEY;
		}

		public static function clearAll()
		{
			return self::getInstance()->getViewCacheProxy()->clearAll();
		}

		/**
		 * @return ViewCacheFacade
		 */
		public static function getInstance()
		{
			return Facade::getInstance( ViewCacheFacade::KEY );
		}
	}