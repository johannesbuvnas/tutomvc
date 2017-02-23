<?php

	namespace tutomvc\wp\pagecache;

	use tutomvc\wp\viewcache\ViewCacheFacade;
	use tutomvc\wp\core\facade\Facade;

	class ViewCacheModule
	{
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