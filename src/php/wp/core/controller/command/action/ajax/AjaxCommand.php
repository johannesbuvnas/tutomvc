<?php
	namespace tutomvc;

	class AjaxCommand extends Command
	{
		/* ACTIONS */
		public function register()
		{
			add_action( "wp_ajax_" . $this->getName(), array(
				$this,
				"onBeforeExecution"
			) );
			add_action( "wp_ajax_nopriv_" . $this->getName(), array(
				$this,
				"onBeforeExecution"
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
				"onBeforeExecution"
			) );
		}
	}