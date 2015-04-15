<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/04/15
	 * Time: 13:37
	 */

	namespace tutomvc\wp\metabox;

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
			// Controller
			$this->controller->registerCommand( new AddMetaBoxesAction() );
		}
	}