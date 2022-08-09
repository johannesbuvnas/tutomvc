<?php
	namespace TutoMVC\WordPress\Core\Model\Proxy;

	use TutoMVC\WordPress\Core\CoreClass;

	class Proxy extends CoreClass
	{
		/* PROTECTED VARS */
		protected $_map = array();
		protected $_name;

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

				return $item;
			}

			return $this->get( $key );
		}

		/**
		 * @param $key
		 *
		 * @return bool
		 */
		public function delete( $key )
		{
			if ( !$key ) return FALSE;
			if ( array_key_exists( $key, $this->_map ) ) unset( $this->_map[ $key ] );

			return TRUE;
		}

		public function has( $key )
		{
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
			return $this->_map;
		}

		public function getName()
		{
			return $this->_name;
		}
	}
