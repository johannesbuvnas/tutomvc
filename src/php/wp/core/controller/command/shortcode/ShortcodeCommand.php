<?php
	namespace tutomvc;

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