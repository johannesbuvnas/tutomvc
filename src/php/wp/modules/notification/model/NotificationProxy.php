<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/11/15
	 * Time: 17:44
	 */

	namespace tutomvc\wp\notification;

	use tutomvc\wp\core\model\proxy\Proxy;

	class NotificationProxy extends Proxy
	{
		const NAME = __CLASS__;

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		public function add( $item, $key = NULL, $override = FALSE )
		{
			if ( !AdminNoticesAction::hasExecuted() )
			{
				return parent::add( $item );
			}
			else
			{
				return $this->addAsSessionCookie( $item );
			}
		}

		/**
		 * @param Notification $notification
		 *
		 * @return mixed
		 */
		public function addAsSessionCookie( $notification )
		{
			if ( array_key_exists( NotificationModule::SESSION_COOKIE, $_SESSION ) ) array_push( $_SESSION[ NotificationModule::SESSION_COOKIE ], $notification );
			else $_SESSION[ NotificationModule::SESSION_COOKIE ] = array(0 => $notification);

			return $notification;
		}

		/* EVENTS */
		function onRegister()
		{
			if ( array_key_exists( NotificationModule::SESSION_COOKIE, $_SESSION ) )
			{
				foreach ( $_SESSION[ NotificationModule::SESSION_COOKIE ] as $key => $notification )
				{
					$this->add( $notification );
				}

				unset($_SESSION[ NotificationModule::SESSION_COOKIE ]);
			}
		}
	}