<?php
	namespace tutomvc\wp\setting;

	use tutomvc\Facade;
	use tutomvc\SystemApp;

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 10/05/15
	 * Time: 08:34
	 */
	class SettingModule
	{
		/**
		 * @param Setting $settings
		 *
		 * @return SettingModuleFacade
		 * @throws \ErrorException
		 */
		public static function add( $settings )
		{
			self::getProxy()->add( $settings );

			return self::getInstance();
		}

		/**
		 * @return SettingModuleFacade
		 */
		public static function getInstance()
		{
			return Facade::getInstance( SettingModuleFacade::KEY ) ? Facade::getInstance( SettingModuleFacade::KEY ) : SystemApp::getInstance()->registerModule( new SettingModuleFacade() );
		}

		/**
		 * @return SettingProxy
		 */
		public static function getProxy()
		{
			return self::getInstance()->getProxy( SettingProxy::NAME );
		}
	}