<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 21/05/15
	 * Time: 09:21
	 */

	namespace TutoMVC\WordPress\Modules\Settings\Controller\Action;

	use TutoMVC\WordPress\Core\Controller\Command\ActionCommand;
	use TutoMVC\WordPress\Modules\Settings\SettingsModule;

	class AdminInitAction extends ActionCommand
	{
		function execute()
		{
			SettingsModule::getProxy()->registerAll();
		}
	}
