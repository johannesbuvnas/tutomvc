<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 23/09/16
	 * Time: 08:24
	 */

	namespace TutoMVC\WordPress\Modules\Exception;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\System\SystemApp;

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
