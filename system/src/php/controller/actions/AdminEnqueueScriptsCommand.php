<?php
namespace tutomvc;

class AdminEnqueueScriptsCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct( "admin_enqueue_scripts" );
	}

	function execute()
	{
		wp_enqueue_media();
		wp_enqueue_script(array
		(
			'jquery',
			'jquery-ui-core',
			'jquery-ui-tabs',
			'jquery-ui-sortable',
			'wp-color-picker',
			'thickbox',
			'media-upload',
			
			'editor',
			'quicktags',
			'tiny_mce',
			'jquery-ui-dialog',
			SystemFacade::SCRIPT_JS_MAIN
		));
    
		wp_enqueue_style ( 'wp-jquery-ui-dialog' );

		wp_enqueue_style( 'tutomvc-components', $this->getFacade()->getURL( "/assets/css/tutomvc.components.css" ), NULL, SystemFacade::VERSION );
		wp_enqueue_style( 'tutomvc-admin', $this->getFacade()->getURL( "/assets/css/tutomvc.admin.css" ), NULL, SystemFacade::VERSION );
	}
}