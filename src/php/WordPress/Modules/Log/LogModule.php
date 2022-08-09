<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 19/11/15
	 * Time: 08:22
	 */

	namespace TutoMVC\WordPress\Modules\Log;

	use TutoMVC\Model\Simple\ValueObject;
	use TutoMVC\Utils\FileUtil;
	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\Modules\AdminMenu\AdminMenuModule;
	use TutoMVC\WordPress\Modules\AdminMenu\Controller\AdminMenuPage;
	use TutoMVC\WordPress\Modules\Log\Controller\LogAdminMenuPage;
	use TutoMVC\WordPress\Modules\Log\Model\LogProxy;
	use function is_multisite;

	class LogModule
	{
		public static function add( $message )
		{
			self::getProxy()->add( new ValueObject( "", $message ) );
		}

		/**
		 * Adds to settings page.
		 */
		public static function addAdminMenuPage()
		{
			if ( is_admin() )
			{
				if ( is_multisite() ) AdminMenuModule::addPageToNetwork( new LogAdminMenuPage( AdminMenuPage::PARENT_SLUG_NETWORK_SETTINGS ) );
				AdminMenuModule::addPage( new LogAdminMenuPage( AdminMenuPage::PARENT_SLUG_SETTINGS ) );
			}
		}

		public static function print_r( $expression )
		{
			self::add( print_r( $expression, TRUE ) );
		}

		public static function clear( $time = 0 )
		{
			return self::getProxy()->delete( $time );
		}

		/**
		 * @return LogModuleFacade
		 */
		public static function getInstance()
		{
			return Facade::getInstance( LogModuleFacade::KEY );
		}

		/**
		 * @param null $relativePath
		 *
		 * @return mixed|string
		 */
		public static function getModuleRoot( $relativePath = NULL )
		{
			return is_null( $relativePath ) ? FileUtil::sanitizePath( dirname( __FILE__ ) ) : FileUtil::sanitizePath( dirname( __FILE__ ) . "/{$relativePath}" );
		}

		/**
		 * @return LogProxy
		 */
		public static function getProxy()
		{
			return self::getInstance()->getProxy( LogProxy::NAME );
		}
	}
