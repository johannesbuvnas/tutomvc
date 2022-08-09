<?php
	namespace TutoMVC\WordPress\Modules\Settings;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\System\SystemApp;
	use TutoMVC\WordPress\Modules\Settings\Controller\Settings;
	use TutoMVC\WordPress\Modules\Settings\Model\SettingsProxy;

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
		 * @param $settingsName
		 *
		 * @return Settings|null
		 */
		public static function get( $settingsName )
		{
			return self::getProxy()->get( $settingsName );
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
