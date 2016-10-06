<?php
	namespace tutomvc\wp\settings;

	use tutomvc\wp\core\facade\Facade;

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 10/05/15
	 * Time: 08:34
	 */
	class SettingsModuleFacade extends Facade
	{
		const KEY = "com.tutomvc.wp.modules.setting";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		function onRegister()
		{
			// Model
			$this->registerProxy( new SettingsProxy() );
			// View
			// Controller
			$this->registerCommand( "admin_init", new AdminInitAction() );
		}
	}