<?php
namespace tutons;

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
			"require-js",
			"tuto-main-js"
		));
    
	   wp_enqueue_style ( 'wp-jquery-ui-dialog' );

		wp_enqueue_style( 'tuto-components', $this->getFacade()->getURL( "/assets/css/tuto.components.css" ), NULL, SystemFacade::VERSION );
		wp_enqueue_style( 'tuto-admin', $this->getFacade()->getURL( "/assets/css/tuto.admin.css" ), NULL, SystemFacade::VERSION );


	}
}