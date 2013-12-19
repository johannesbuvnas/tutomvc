<?php
namespace tutons;

class ActionCommand extends Command
{
	/* COMMAND NAMES BY TUTO */
	const START_UP = "tuto/action/startup";

	const FACADE_READY = "tuto/action/facade/ready";

	const RENDER_META_BOX = "tuto/action/render/metabox";
	const RENDER_WP_EDITOR = "tuto/action/render/wp_editor";
	
	/* PUBLIC VARS */
	public $priority = 10;
	public $acceptedArguments = 1;


	public function register()
	{
		add_action( $this->getName(), array( $this, "preExecution" ), $this->priority, $this->acceptedArguments );
	}
}