<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 19/11/15
	 * Time: 08:22
	 */
	namespace tutomvc\wp\log;

	use tutomvc\Facade;
	use tutomvc\ValueObject;

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

		/**
		 * @return LogModuleFacade
		 */
		public static function getInstance()
		{
			return Facade::getInstance( LogModuleFacade::KEY ) ? Facade::getInstance( LogModuleFacade::KEY ) : Facade::getInstance( Facade::KEY_SYSTEM )->registerSubFacade( new LogModuleFacade() );
		}

		/**
		 * @return LogProxy
		 */
		public static function getProxy()
		{
			return self::getInstance()->model->getProxy( LogProxy::NAME );
		}
	}