<?php
namespace tutomvc;

class ActionCommand extends Command
{
	/* ACTION COMMANDS */
	const START_UP = "tutomvc/action/startup";

	const FACADE_READY = "tutomvc/action/facade/ready";
	const ATTACHMENT_UPLOADED = "tutomvc/action/attachment/uploaded";

	const RENDER_META_BOX = "tutomvc/action/render/metabox";
	const RENDER_WP_EDITOR = "tutomvc/action/render/wp_editor";
	const RENDER_ADMIN_MENU_PAGE = "tutomvc/action/render/admin_menu_page";
	const RENDER_SETTINGS_FIELD = "tutomvc/action/render/settings_field";

	const PREPARE_META_FIELD = "tutomvc/action/prepare/meta/field";

	const GIT_PULL = "tutomvc/action/git/pull";
	const GIT_ADD = "tutomvc/action/git/add";

	/* PUBLIC VARS */
	public $priority = 10;
	public $acceptedArguments = 1;


	public function register( $name = NULL )
	{
		add_action( $name ? $name : $this->getName(), array( $this, "preExecution" ), $this->priority, $this->acceptedArguments );
	}
}
