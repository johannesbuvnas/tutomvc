<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 19/11/15
	 * Time: 08:22
	 */

	namespace tutomvc\wp\log;

	use tutomvc\core\model\ValueObject;
	use tutomvc\core\utils\FileUtil;
	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\system\SystemApp;

	class LogModule
	{
		public static function add( $message )
		{
			self::getProxy()->add( new ValueObject( "", $message ) );
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
			return Facade::getInstance( LogModuleFacade::KEY ) ? Facade::getInstance( LogModuleFacade::KEY ) : SystemApp::getInstance()->registerModule( new LogModuleFacade() );
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