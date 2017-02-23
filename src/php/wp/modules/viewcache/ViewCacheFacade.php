<?php

	namespace tutomvc\wp\viewcache;

	use tutomvc\wp\viewcache\model\proxy\ViewCacheProxy;
	use tutomvc\wp\core\facade\Facade;

	class ViewCacheFacade extends Facade
	{
		const KEY = "tutomvc_viewcache";

		public function __construct()
		{
			parent::__construct( self::KEY );
		}

		protected function prepModel()
		{
			$this->registerProxy( new ViewCacheProxy() );
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
					$this->getViewCacheProxy()->add( $output, $key, TRUE );
					if ( $returnOutput ) return $output;
					echo $output;
				}
			}

			return NULL;
		}

		/**
		 * @return ViewCacheProxy
		 */
		public function getViewCacheProxy()
		{
			return $this->getProxy( ViewCacheProxy::NAME );
		}
	}