<?php

	namespace tutomvc\wp\viewcache;

	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\viewcache\model\proxy\ViewCacheProxy;

	class ViewCacheFacade extends Facade
	{
		const KEY                                = "tutomvc_viewcache";
		const HTML_COMMENT_IGNORE_VIEW_CACHE_KEY = "<!-- [ignore_view_cache] -->";

		protected $_cacheExpirationTimeInSeconds = 0;

		public function __construct( $cacheExpirationTimeInSeconds = 0 )
		{
			parent::__construct( self::KEY );

			$this->_cacheExpirationTimeInSeconds = $cacheExpirationTimeInSeconds;
		}

		protected function prepModel()
		{
			$this->registerProxy( new ViewCacheProxy( $this->_cacheExpirationTimeInSeconds ) );
		}

		public function render( $relativePath, $name = NULL, $dataProvider = array(), $returnOutput = FALSE )
		{
			if ( $this->isViewComponent( $relativePath, $name ) )
			{
				$key = $this->getViewCacheProxy()->formatKey( $_SERVER[ 'REQUEST_URI' ], $relativePath, $name, $dataProvider );
				if ( !empty( $output = $this->getViewCacheProxy()->get( $key ) ) )
				{
					if ( $returnOutput ) return $output;
					echo $output;
				}
				else
				{
					$output = parent::render( $relativePath, $name, $dataProvider, TRUE );
					if ( !$this->hasIgnoreCacheKey( $output ) ) $this->getViewCacheProxy()->add( $output, $key, TRUE );
					if ( $returnOutput ) return $output;
					echo $output;
				}
			}

			return NULL;
		}

		function hasIgnoreCacheKey( $html )
		{
			return stripos( $html, self::HTML_COMMENT_IGNORE_VIEW_CACHE_KEY ) !== FALSE;
		}

		/**
		 * @return ViewCacheProxy
		 */
		public function getViewCacheProxy()
		{
			return $this->getProxy( ViewCacheProxy::NAME );
		}
	}