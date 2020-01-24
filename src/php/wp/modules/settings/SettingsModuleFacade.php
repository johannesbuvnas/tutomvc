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

		protected function prepModel()
		{
			$this->registerProxy( new SettingsProxy() );
		}

		protected function prepController()
		{
			$this->registerCommand( "admin_init", new AdminInitAction() );
		}
	}