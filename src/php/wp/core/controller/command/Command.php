<?php
	namespace tutomvc\wp;

	class Command extends CoreClass implements ICommand
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
			if ( $this->hasReachedExecutionLimit() ) return;

			$this->_executions ++;

			return call_user_func_array( array($this, "execute"), func_get_args() );
		}
	}

	interface ICommand
	{
		public function setName( $name );

		public function getName();

		public function register();

		public function setExecutionLimit( $limit );

		public function getExecutionLimit();

		public function getExecutionCount();

		public function hasReachedExecutionLimit();

		public function onBeforeExecution();
	}