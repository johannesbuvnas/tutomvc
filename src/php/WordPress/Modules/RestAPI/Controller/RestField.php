<?php

	namespace TutoMVC\WordPress\Modules\RestAPI\Controller;

	use WP_REST_Request;

	class RestField
	{
		protected $_objectType;
		protected $_name;

		/**
		 * RestField constructor.
		 *
		 * @param string|array $objectType
		 * @param string $name
		 */
		public function __construct( $objectType, $name )
		{
			$this->_objectType = $objectType;
			$this->_name       = $name;
		}

		/* METHODS */
		/**
		 * @param object|array $object
		 * @param string $fieldName
		 * @param WP_REST_Request $request
		 * @param string $objectType
		 *
		 * @return null
		 */
		public function callback_get( $object, $fieldName, $request, $objectType )
		{
			return NULL;
		}

		/**
		 * @param mixed $value
		 * @param object|array $object
		 * @param string $fieldName
		 * @param WP_REST_Request $request
		 * @param string $objectType
		 *
		 * @return null
		 */
		public function callback_update( $value, $object, $fieldName, $request, $objectType )
		{
			return NULL;
		}

		public function callback_schema()
		{
			return NULL;
		}

		/**
		 * @return string|array
		 */
		public function getObjectType()
		{
			return $this->_objectType;
		}

		/**
		 * @return string
		 */
		public function getName()
		{
			return $this->_name;
		}
	}
