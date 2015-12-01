<?php
	namespace tutomvc;

	class ActionCommand extends Command
	{
		/* PUBLIC VARS */
		public $priority          = 10;
		public $acceptedArguments = 1;

		function __construct( $priority = 10, $acceptedArguments = 1 )
		{
			$this->priority          = $priority;
			$this->acceptedArguments = $acceptedArguments;
		}

		public function register()
		{
			add_action( $this->getName(), array($this, "onBeforeExecution"), $this->priority, $this->acceptedArguments );
		}
	}
