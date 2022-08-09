<?php

	namespace TutoMVC\WordPress\Modules\RestAPI;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\System\SystemApp;
	use TutoMVC\WordPress\Modules\RestAPI\RestAPIModuleFacade;
	use TutoMVC\WordPress\Modules\RestAPI\Controller\RestField;
	use TutoMVC\WordPress\Modules\RestAPI\Model\RestFieldProxy;
	use TutoMVC\WordPress\Modules\RestAPI\Controller\RestRoute;
	use TutoMVC\WordPress\Modules\RestAPI\Model\RestRouteProxy;

	class RestAPIModule
	{
		public static function registerField( RestField $restField )
		{
			self::getRestFieldProxy()->add( $restField );
		}

		public static function registerRoute( RestRoute $restRoute )
		{
			self::getRestRouteProxy()->add( $restRoute );
		}

		/**
		 * @return RestRouteProxy
		 */
		public static function getRestRouteProxy()
		{
			return self::getInstance()->getProxy( RestRouteProxy::NAME );
		}

		/**
		 * @return RestFieldProxy
		 */
		public static function getRestFieldProxy()
		{
			return self::getInstance()->getProxy( RestFieldProxy::NAME );
		}

		/**
		 * @return RestAPIModuleFacade
		 */
		public static function getInstance()
		{
			return Facade::getInstance( RestAPIModuleFacade::KEY ) ? Facade::getInstance( RestAPIModuleFacade::KEY ) : SystemApp::getInstance()->registerModule( new RestAPIModuleFacade() );
		}
	}
