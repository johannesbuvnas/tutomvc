<?php
namespace tutomvc;

class ActionCommand extends Command
{
	/* COMMAND NAMES BY TUTO */
	const START_UP = "tutomvc/action/startup";

	const FACADE_READY = "tutomvc/action/facade/ready";

	const RENDER_META_BOX = "tutomvc/action/render/metabox";
	const RENDER_WP_EDITOR = "tutomvc/action/render/wp_editor";
	const RENDER_ADMIN_MENU_PAGE = "tutomvc/action/render/admin_menu_page";
	const RENDER_SETTINGS_FIELD = "tutomvc/action/render/settings_field";

	const PREPARE_META_FIELD = "tutomvc/action/prepare/meta/field";
	
	/* PUBLIC VARS */
	public $priority = 10;
	public $acceptedArguments = 1;


	public function register()
	{
		add_action( $this->getName(), array( $this, "preExecution" ), $this->priority, $this->acceptedArguments );
	}
}