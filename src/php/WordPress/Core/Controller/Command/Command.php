<?php
	namespace TutoMVC\WordPress\Core\Controller\Command;

	use TutoMVC\WordPress\Core\CoreClass;

	class Command extends CoreClass
	{
		/* VARS */
		protected $_name;
		protected $_executionLimit = - 1;
		protected $_executions     = 0;
		protected $_args;

		/* PUBLIC METHODS */
		/**
		 * Override.
		 */
		public function register()
		{
		}

		/**
		 * Override.
		 *
		 * @param $args
		 */
		public function execute()
		{
		}

		public function remove()
		{
		}

		public function setName( $name )
		{
			$this->_name = $name;
		}

		public function getName()
		{
			return $this->_name;
		}

		public function setExecutionLimit( $limit )
		{
			$this->_executionLimit = $limit;
		}

		public function getExecutionLimit()
		{
			return $this->_executionLimit;
		}

		public function getExecutionCount()
		{
			return $this->_executions;
		}

		public function hasReachedExecutionLimit()
		{
			return $this->_executionLimit > - 1 && $this->_executions >= $this->_executionLimit;
		}

		/* EVENTS */
		public function onBeforeExecution()
		{
			if ( $this->hasReachedExecutionLimit() ) return NULL;

			$this->_executions ++;

			return call_user_func_array( array($this, "execute"), func_get_args() );
		}
	}
