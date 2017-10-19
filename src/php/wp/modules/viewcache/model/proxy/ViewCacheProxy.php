<?php

	namespace tutomvc\wp\viewcache\model\proxy;

	use tutomvc\wp\core\model\proxy\CacheableProxy;

	class ViewCacheProxy extends CacheableProxy
	{
		const NAME = "view_cache_proxy";

		function __construct( $cacheExpirationInSeconds )
		{
			parent::__construct( self::NAME, $cacheExpirationInSeconds );
		}

		public function formatKey( $requestURI, $viewComponent, $name, $dataProvider )
		{
			if ( $this->getFacade()->isViewComponent( $viewComponent, $name ) )
			{
				$query = array(
					"requestURI"    => $requestURI,
					"viewComponent" => $this->getFacade()->getViewComponentRealpath( $viewComponent, $name ),
					"dp"            => $dataProvider
				);

				return md5( http_build_query( $query ) );
			}

			return FALSE;
		}

		public function getCacheGroupName()
		{
			$blogID = get_current_blog_id();

			return "blog-id-$blogID/" . $this->getName();
		}
	}