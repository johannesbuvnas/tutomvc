<?php
	namespace TutoMVC\WordPress\Core\Controller\Command;

	use TutoMVC\WordPress\Core\Controller\Command\ActionCommand;
	use function tutomvc\wp\core\controller\command\add_filter;
	use function tutomvc\wp\core\controller\command\remove_filter;

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
