<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/04/15
	 * Time: 13:37
	 * TODO: Create jQuery plugin WPEditor
	 * TODO: Create jQuery plugin WPAttachment
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\wp\TutoMVC;

	class MetaBoxModuleFacade extends \tutomvc\wp\Facade
	{
		const KEY = "com.tutomvc.wp.modules.metabox";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		function onRegister()
		{
			parent::onRegister();

			if ( is_admin() )
			{
				//TODO: Move to action? admin_enqueue_scripts
//				wp_enqueue_style( "bootstrap-selectpicker", $this->getURL( "bower_components/bootstrap-select/dist/css/bootstrap-select.min.css" ), NULL, TutoMVC::VERSION );
//				wp_enqueue_style( "select2", $this->getURL( "bower_components/select2/dist/css/select2.min.css" ), NULL, TutoMVC::VERSION );
//
//				wp_enqueue_script( "jquery-ui-sortable" );
//				wp_enqueue_script( "select2", $this->getURL( "bower_components/select2/dist/js/select2.full.min.js" ), NULL, TutoMVC::VERSION );
//				wp_enqueue_script( "bootstrap", $this->getURL( "bower_components/bootstrap/dist/js/bootstrap.min.js" ), NULL, TutoMVC::VERSION );
//				wp_enqueue_script( "bootstrap-selectpicker", $this->getURL( "bower_components/bootstrap-select/dist/js/bootstrap-select.min.js" ), NULL, TutoMVC::VERSION );
			}
		}

		function prepModel()
		{
			$this->registerProxy( new MetaBoxProxy() );
		}

		function prepController()
		{
			$this->registerCommand( "admin_init", new AdminInitAction() );
			$this->registerCommand( "add_meta_boxes", new AddMetaBoxesAction( 10, 2 ) );
			$this->registerCommand( "get_post_metadata", new GetPostMetadataFilter( 99, 4 ) );
//			$this->registerCommand( "admin_enqueue_scripts", new AdminEnqueueScriptsAction() );
		}
	}