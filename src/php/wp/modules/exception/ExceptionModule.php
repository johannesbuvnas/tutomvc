<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 23/09/16
	 * Time: 08:24
	 */

	namespace tutomvc\wp\exception;

	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\system\SystemApp;

	class ExceptionModule
	{
		/**
		 * @return ExceptionModuleFacade
		 */
		public static function activate()
		{
			return self::getInstance();
		}

		/**
		 * @return ExceptionModuleFacade
		 */
		public static function getInstance()
		{
			return Facade::getInstance( ExceptionModuleFacade::KEY ) ? Facade::getInstance( ExceptionModuleFacade::KEY ) : SystemApp::getInstance()->registerModule( new ExceptionModuleFacade() );
		}
	}