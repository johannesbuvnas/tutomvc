<?php
	namespace TutoMVC\WordPress\Core\Controller\Command;

	use TutoMVC\WordPress\Core\Controller\Command\Command;
	use function tutomvc\wp\core\controller\command\add_shortcode;
	use function tutomvc\wp\core\controller\command\remove_shortcode;

	class ShortcodeCommand extends Command
	{
		public function register()
		{
			add_shortcode( $this->getName(), array($this, "onBeforeExecution") );
		}

		public function remove()
		{
			remove_shortcode( $this->getName() );
		}
	}
