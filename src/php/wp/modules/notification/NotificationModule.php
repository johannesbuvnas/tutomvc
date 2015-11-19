<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/11/15
	 * Time: 17:40
	 */

	namespace tutomvc\wp\notification;

	use tutomvc\Facade;

	class NotificationModule
	{
		const SESSION_COOKIE  = "com.tutomvc.system.session.cookie.notifications";
		const TYPE_UPDATE     = "updated";
		const TYPE_ERROR      = "error";
		const TYPE_UPDATE_NAG = "update-nag";

		public static function add( $content, $type = NotificationModule::TYPE_UPDATE, $addAsSessionCookie = FALSE )
		{
			$notification = new Notification( $content, $type );
			if ( $addAsSessionCookie ) self::getProxy()->addAsSessionCookie( $notification );
			else self::getProxy()->add( $notification );
		}

		/**
		 * @return NotificationModuleFacade
		 */
		public static function getInstance()
		{
			return Facade::getInstance( NotificationModuleFacade::KEY ) ? Facade::getInstance( NotificationModuleFacade::KEY ) : Facade::getInstance( Facade::KEY_SYSTEM )->registerSubFacade( new NotificationModuleFacade() );
		}

		/**
		 * @return NotificationProxy
		 */
		public static function getProxy()
		{
			return self::getInstance()->model->getProxy( NotificationProxy::NAME );
		}
	}