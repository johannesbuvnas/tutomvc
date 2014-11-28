<?php
namespace tutomvc;

class AdminFooterCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct( "admin_footer" );
	}

	function execute()
	{
		do_action( ActionCommand::RENDER_WP_EDITOR );
	}
}