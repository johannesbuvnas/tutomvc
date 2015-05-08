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

	use tutomvc\TutoMVC;

	class MetaBoxModuleFacade extends \tutomvc\Facade
	{
		const KEY = "tutomvc/modules/metabox";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		function onRegister()
		{
			// Model
			$this->model->registerProxy( new MetaBoxProxy() );
			if ( is_admin() )
			{
				// TODO: Enqueue scripts that is needed for this module
				wp_enqueue_style( "tutomvc-bootstrap", $this->getURL( "dist/css/style.css" ), NULL, TutoMVC::VERSION );
				wp_enqueue_style( "bootstrap-selectpicker", $this->getURL( "libs/scripts/bootstrap-select/dist/css/bootstrap-select.min.css" ), NULL, TutoMVC::VERSION );
				wp_enqueue_style( "select2", $this->getURL( "libs/scripts/select2/dist/css/select2.min.css" ), NULL, TutoMVC::VERSION );

				wp_enqueue_script( "jquery-ui-sortable" );
				wp_enqueue_script( "select2", $this->getURL( "libs/scripts/select2/dist/js/select2.full.min.js" ), NULL, TutoMVC::VERSION );
				wp_enqueue_script( "bootstrap", $this->getURL( "libs/scripts/bootstrap/dist/js/bootstrap.min.js" ), NULL, TutoMVC::VERSION );
				wp_enqueue_script( "bootstrap-selectpicker", $this->getURL( "libs/scripts/bootstrap-select/dist/js/bootstrap-select.min.js" ), NULL, TutoMVC::VERSION );
			}
			// Controller
			$this->controller->registerCommand( new AddMetaBoxesAction() );
			$this->controller->registerCommand( new SavePostAction() );
			$this->controller->registerCommand( new GetPostMetadataFilter() );
		}
	}