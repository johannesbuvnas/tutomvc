<?php

	namespace TutoMVC\WordPress\Modules\Settings;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\Modules\Settings\Controller\Action\AdminInitAction;
	use TutoMVC\WordPress\Modules\Settings\Model\SettingsProxy;

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
