<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/11/15
	 * Time: 17:40
	 */

	namespace TutoMVC\WordPress\Modules\Notices;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\System\SystemApp;
	use TutoMVC\WordPress\Modules\Notices\Model\Notice;
	use TutoMVC\WordPress\Modules\Notices\Model\NoticesProxy;
	use function var_dump;

	class NoticesModule
	{
		const TYPE_UPDATE     = "updated";
		const TYPE_ERROR      = "error";
		const TYPE_UPDATE_NAG = "update-nag";

		public static function add( $content, $type = NoticesModule::TYPE_UPDATE, $addAsSessionCookie = FALSE )
		{
			$notification = new Notice( $content, $type );
			if ( $addAsSessionCookie ) self::getProxy()->addAsSessionCookie( $notification );
			else self::getProxy()->add( $notification );
		}

		/**
		 * @return NoticesModuleFacade
		 */
		public static function getInstance()
		{
			return Facade::getInstance( NoticesModuleFacade::KEY ) ? Facade::getInstance( NoticesModuleFacade::KEY ) : SystemApp::getInstance()->registerModule( new NoticesModuleFacade() );
		}

		/**
		 * @return NoticesProxy
		 */
		public static function getProxy()
		{
			return self::getInstance()->getProxy( NoticesProxy::NAME );
		}
	}
