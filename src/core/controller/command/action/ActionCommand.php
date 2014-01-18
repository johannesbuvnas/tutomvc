<?php
namespace tutomvc;

class ActionCommand extends Command
{
	/* COMMAND NAMES BY TUTO */
	const START_UP = "tutomvc/action/startup";

	const FACADE_READY = "tutomvc/action/facade/ready";

	const RENDER_META_BOX = "tutomvc/action/render/metabox";
	const RENDER_WP_EDITOR = "tutomvc/action/render/wp_editor";
	
	/* PUBLIC VARS */
	public $priority = 10;
	public $acceptedArguments = 1;


	public function register()
	{
		add_action( $this->getName(), array( $this, "preExecution" ), $this->priority, $this->acceptedArguments );
	}
}