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

	use tutomvc\wp\core\facade\Facade;

	class MetaBoxModuleFacade extends Facade
	{
		const KEY = "com.tutomvc.wp.modules.metabox";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		function onRegister()
		{
			parent::onRegister();
		}

		function prepModel()
		{
			$this->registerProxy( new MetaBoxProxy() );
			$this->registerProxy( new UserMetaBoxProxy() );
		}

		function prepController()
		{
			$this->registerCommand( "admin_init", new AdminInitAction() );
			$this->registerCommand( "add_meta_boxes", new AddMetaBoxesAction( 10, 2 ) );
			$this->registerCommand( "show_user_profile", new RenderUserMetaBoxesAction( 10, 1 ) );
			$this->registerCommand( "edit_user_profile", new RenderUserMetaBoxesAction( 10, 1 ) );
			$this->registerCommand( "get_post_metadata", new GetPostMetadataFilter( 99, 4 ) );
		}
	}