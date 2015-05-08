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
				wp_enqueue_style( "tutomvc-bootstrap", $this->getSystem()->getURL( "dist/css/style.css" ), NULL, TutoMVC::VERSION );
//				wp_enqueue_script( "jquery-ui-sortable" );
			}
			// Controller
			$this->controller->registerCommand( new AddMetaBoxesAction() );
			$this->controller->registerCommand( new SavePostAction() );
			$this->controller->registerCommand( new GetPostMetadataFilter() );
			// TODO: Enqueue scripts that is needed for this module
		}
	}