<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 11/03/15
	 * Time: 11:58
	 */

	namespace tutomvc\wp\posttype;

	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\system\SystemApp;

	class PostTypeModule
	{
		public static function add( PostType $postType )
		{
			self::getProxy()->add( $postType, $postType->getName() );
		}

		public static function getInstance()
		{
			return Facade::getInstance( PostTypeModuleFacade::KEY ) ? Facade::getInstance( PostTypeModuleFacade::KEY ) : SystemApp::getInstance()->registerModule( new PostTypeModuleFacade() );
		}

		public static function getProxy()
		{
			return self::getInstance()->getProxy( PostTypeProxy::NAME );
		}
	}