<?php
	namespace tutomvc\wp\settings;

	use tutomvc\Facade;
	use tutomvc\SystemApp;

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 10/05/15
	 * Time: 08:34
	 */
	class SettingsModule
	{
		/**
		 * @param Settings $settings
		 *
		 * @return SettingsModuleFacade
		 * @throws \ErrorException
		 */
		public static function add( $settings )
		{
			self::getProxy()->add( $settings );

			return self::getInstance();
		}

		/**
		 * @return SettingsModuleFacade
		 */
		public static function getInstance()
		{
			return Facade::getInstance( SettingsModuleFacade::KEY ) ? Facade::getInstance( SettingsModuleFacade::KEY ) : SystemApp::getInstance()->registerModule( new SettingsModuleFacade() );
		}

		/**
		 * @return SettingsProxy
		 */
		public static function getProxy()
		{
			return self::getInstance()->getProxy( SettingsProxy::NAME );
		}
	}