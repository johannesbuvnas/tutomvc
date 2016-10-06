<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 21/05/15
	 * Time: 09:21
	 */

	namespace tutomvc\wp\settings;

	use tutomvc\wp\core\controller\command\ActionCommand;

	class AdminInitAction extends ActionCommand
	{
		function execute()
		{
			SettingsModule::getProxy()->registerAll();
		}
	}