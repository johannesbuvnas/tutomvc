<?php
	namespace TutoMVC\WordPress\Core\Controller\Command;

	class FilterCommand extends ActionCommand
	{

		public function register()
		{
			add_filter( $this->getName(), array(
				$this,
				"onBeforeExecution"
			), $this->priority, $this->acceptedArguments );
		}

		public function remove()
		{
			remove_filter( $this->getName(), array($this, "onBeforeExecution"), $this->priority );
		}
	}
