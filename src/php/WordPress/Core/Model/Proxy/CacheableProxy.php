<?php

	namespace TutoMVC\WordPress\Core\Model\Proxy;

	use TutoMVC\WordPress\Core\Model\Proxy\Proxy;

	class CacheableProxy extends Proxy
	{
		protected $_cacheExpirationTimeInSeconds = 0;

		function __construct( $name = NULL, $cacheExpirationTimeInSeconds = 0 )
		{
			parent::__construct( $name );
			$this->setCacheExpirationTimeInSeconds( $cacheExpirationTimeInSeconds );
		}

		public function clear( $key )
		{
			return $this->getCacheDriver()->delete( $key, $this->getCacheGroupName() );
		}

		public function clearAll()
		{
			return $this->getCacheDriver()->deleteGroup( $this->getCacheGroupName() );
		}

		public function add( $item, $key = NULL, $override = FALSE )
		{
			$key = is_null( $key ) ? uniqid() : $key;

			$return = parent::add( $item, $key, $override );

			$this->getCacheDriver()->set( $key, $item, $this->getCacheGroupName(), $this->getCacheExpirationTimeInSeconds() );

			return $return;
		}

		public function get( $key )
		{
			return parent::get( $key );
		}

		public function delete( $key )
		{
			$this->getCacheDriver()->delete( $key, $this->getCacheGroupName() );

			return parent::delete( $key );
		}

		public function has( $key )
		{
			if ( !parent::has( $key ) )
			{
				$cached = $this->getCacheDriver()->get( $key, $this->getCacheGroupName() );
				if ( !is_null( $cached ) ) $this->_map[ $key ] = $cached;
			}

			return parent::has( $key );
		}

		/**
		 * @param int $cacheExpirationTimeInSeconds
		 */
		public function setCacheExpirationTimeInSeconds( $cacheExpirationTimeInSeconds )
		{
			$this->_cacheExpirationTimeInSeconds = $cacheExpirationTimeInSeconds;
		}

		/**
		 * @return int
		 */
		public function getCacheExpirationTimeInSeconds()
		{
			return $this->_cacheExpirationTimeInSeconds;
		}

		public function getCacheGroupName()
		{
			return "proxy/" . $this->getFacadeKey() . "/" . $this->getName();
		}

		/**
		 * @return \TutoMVC\WordPress\Core\Model\Cache\ICacheDriver
		 */
		public function getCacheDriver()
		{
			return $this->getFacade()->getCacheDriver();
		}
	}
