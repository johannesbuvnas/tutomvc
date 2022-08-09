<?php

	namespace TutoMVC\WordPress\Core\Controller\Command;

	use function call_user_func_array;
	use function func_get_args;

	class AjaxCommand extends Command
	{
		/* ACTIONS */
		public function executeNoPriv()
		{

		}

		public function register()
		{
			add_action( "wp_ajax_" . $this->getName(), array(
				$this,
				"onBeforeExecution"
			) );
			add_action( "wp_ajax_nopriv_" . $this->getName(), array(
				$this,
				"onBeforeNoPrivExecution"
			) );
		}

		public function remove()
		{
			remove_action( "wp_ajax_" . $this->getName(), array(
				$this,
				"onBeforeExecution"
			) );
			remove_action( "wp_ajax_nopriv_" . $this->getName(), array(
				$this,
				"onBeforeNoPrivExecution"
			) );
		}

		public function onBeforeNoPrivExecution()
		{
			if ( $this->hasReachedExecutionLimit() ) return NULL;

			$this->_executions ++;

			return call_user_func_array( array($this, "executeNoPriv"), func_get_args() );
		}
	}
