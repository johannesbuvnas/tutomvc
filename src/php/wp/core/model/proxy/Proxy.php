<?php
	namespace tutomvc;

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

				if ( $this->getCacheEnabled() )
				{
					wp_cache_set( $key, $item, $this->getCacheGroupName(), $this->_cacheExpirationTimeInSeconds );
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
			if ( $this->getCacheEnabled() ) wp_cache_delete( $key, $this->getCacheGroupName() );

			return $this;
		}

		public function has( $key )
		{
			if ( $this->getCacheEnabled() && wp_cache_get( $key, $this->getCacheGroupName() ) !== FALSE ) $this->_map[ $key ] = wp_cache_get( $key, $this->getCacheGroupName() );

			return array_key_exists( $key, $this->_map );
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
			return $this->_map;
		}

		public function getName()
		{
			return $this->_name;
		}

		/**
		 *    Use WP Cache to cache all items added to this proxy.
		 */
		public function setCacheEnabled( $value, $cacheExpirationTimeInSeconds = 0 )
		{
			$this->_cacheEnabled = $value;
			$this->_cacheExpirationTimeInSeconds;

			return $this;
		}

		public function getCacheEnabled()
		{
			return $this->_cacheEnabled;
		}

		public function getCacheGroupName()
		{
			return $this->getFacadeKey() . "_proxy_" . $this->getName();
		}
	}