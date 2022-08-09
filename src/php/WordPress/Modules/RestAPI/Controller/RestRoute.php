<?php

	namespace TutoMVC\WordPress\Modules\RestAPI\Controller;

	use WP_REST_Request;
	use WP_REST_Server;

	class RestRoute
	{
		protected $_namespace;
		protected $_route;
		protected $_methods;
		protected $_args;
		protected $_override;

		public function __construct( $namespace, $route, $methods = WP_REST_Server::READABLE, $args = array(), $override = FALSE )
		{
			$this->_namespace = $namespace;
			$this->_route     = $route;
			$this->_methods   = $methods;
			$this->_args      = $args;
			$this->_override  = $override;
		}

		public function callback( WP_REST_Request $data )
		{
			return NULL;
		}

		public function callback_permission()
		{
			return TRUE;
		}

		/**
		 * @return mixed
		 */
		public function getNamespace()
		{
			return $this->_namespace;
		}

		/**
		 * @return mixed
		 */
		public function getRoute()
		{
			return $this->_route;
		}

		/**
		 * @return mixed
		 */
		public function getMethods()
		{
			return $this->_methods;
		}

		/**
		 * @return bool
		 */
		public function isOverride(): bool
		{
			return $this->_override;
		}

		/**
		 * @return mixed
		 */
		public function getArgs()
		{
			return $this->_args;
		}
	}
