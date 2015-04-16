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
			wp_enqueue_script( array
			(
				'jquery',
				'jquery-ui-core',
				'jquery-ui-tabs',
				'jquery-ui-sortable',
				'wp-color-picker',
				'thickbox',
				'media-upload',
				'wpdialogs-popup',
				'editor',
				'quicktags',
				'tiny_mce',
				'jquery-ui-dialog',
				TutoMVC::NAME,
				SystemFacade::SCRIPT_JS,
				"tutomvc-backbone"
			) );

			wp_enqueue_style( array(
				'wp-jquery-ui-dialog',
				SystemFacade::STYLE_CSS
			) );

//			wp_enqueue_style( "tutomvc-bootstrap", $this->getSystem()->getURL( "dist/css/style.css" ), NULL, TutoMVC::VERSION );

		}
	}