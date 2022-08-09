<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 11/03/15
	 * Time: 11:58
	 */

	namespace TutoMVC\WordPress\Modules\PostType;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\System\SystemApp;
	use TutoMVC\WordPress\Modules\PostType\Controller\PostType;
	use TutoMVC\WordPress\Modules\PostType\PostTypeModuleFacade;
	use TutoMVC\WordPress\Modules\PostType\Model\PostTypeProxy;

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
