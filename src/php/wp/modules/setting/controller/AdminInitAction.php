<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 21/05/15
	 * Time: 09:21
	 */

	namespace tutomvc\wp\setting;

	use tutomvc\ActionCommand;

	class AdminInitAction extends ActionCommand
	{
		const NAME = "admin_init";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function execute()
		{
			SettingModule::getProxy()->registerAll();
		}
	}