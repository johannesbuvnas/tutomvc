<?php
	namespace tutomvc\wp\setting;

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 10/05/15
	 * Time: 08:34
	 */
	class SettingModuleFacade extends \tutomvc\Facade
	{
		const KEY = "tutomvc/modules/setting";

		function onRegister()
		{
			// Model
			$this->model->registerProxy( new SettingProxy() );
			// View
			// Controller
		}
	}