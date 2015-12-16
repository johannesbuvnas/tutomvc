<?php
	namespace tutomvc\wp;

	class Proxy extends CoreClass
	{
		/* PROTECTED VARS */
		protected $_map                          = array();
		protected $_name;
		protected $_cacheEnabled                 = FALSE;
		protected $_cacheExpirationTimeInSeconds = 0;

		function __construct( $name = NULL )
		{
			$this->_name = is_null( $name ) ? get_class( $this ) : $name;
		}

		function onRegister()
		{
		}

		/**
		 * @param mixed $item
		 * @param null $key
		 * @param bool $override
		 *
		 * @return mixed
		 */
		public function add( $item, $key = NULL, $override = FALSE )
		{
			$key = is_null( $key ) ? uniqid() : $key;

			if ( $override ) $this->delete( $key );
			if ( !$this->has( $key ) )
			{
				$this->_map[ $key ] = $item;

				if ( $this->isCacheEnabled() )
				{
					wp_cache_set( $key, $item, $this->getCacheGroupName(), $this->getCacheExpirationTimeInSeconds() );
					wp_cache_set( $this->getCacheMapKeyName(), $this->_map, $this->getCacheGroupName(), $this->getCacheExpirationTimeInSeconds() );
				}

				return $item;
			}
			else
			{
				return $this->get( $key );
			}
		}

		public function delete( $key )
		{
			if ( !$key ) return $this;
			if ( array_key_exists( $key, $this->_map ) ) unset($this->_map[ $key ]);
			if ( $this->isCacheEnabled() )
			{
				wp_cache_delete( $key, $this->getCacheGroupName() );
				wp_cache_set( $this->getCacheMapKeyName(), $this->_map, $this->getCacheGroupName(), $this->getCacheExpirationTimeInSeconds() );
			}

			return $this;
		}

		public function has( $key )
		{
			if ( $this->isCacheEnabled() && wp_cache_get( $key, $this->getCacheGroupName() ) !== FALSE ) $this->_map[ $key ] = wp_cache_get( $key, $this->getCacheGroupName() );

			return array_key_exists( $key, $this->_map );
		}

		public function count()
		{
			return count( $this->_map );
		}

		/**
		 * @param $key
		 *
		 * @return mixed|null
		 */
		public function get( $key )
		{
			return $this->has( $key ) ? $this->_map[ $key ] : NULL;
		}

		public function getMap()
		{
			if ( empty($this->_map) && $this->isCacheEnabled() === TRUE )
			{
				$map = wp_cache_get( $this->getCacheMapKeyName(), $this->getCacheGroupName() );
				if ( is_array( $map ) ) $this->_map = $map;
			}

			return $this->_map;
		}

		public function getName()
		{
			return $this->_name;
		}

		/**
		 *    Use WP Cache to cache all items added to this proxy.
		 *
		 * @param bool $value
		 * @param int $cacheExpirationTimeInSeconds
		 *
		 * @return $this
		 */
		public function setCacheEnabled( $value, $cacheExpirationTimeInSeconds = 0 )
		{
			$this->_cacheEnabled                 = $value;
			$this->_cacheExpirationTimeInSeconds = $cacheExpirationTimeInSeconds;
		}

		public function isCacheEnabled()
		{
			return $this->_cacheEnabled;
		}

		public function getCacheGroupName()
		{
			return $this->getFacadeKey() . "_proxy_" . $this->getName();
		}

		public function getCacheMapKeyName()
		{
			return $this->getFacadeKey() . "_proxy_map_" . $this->getName();
		}

		/**
		 * @return int
		 */
		public function getCacheExpirationTimeInSeconds()
		{
			return $this->_cacheExpirationTimeInSeconds;
		}
	}