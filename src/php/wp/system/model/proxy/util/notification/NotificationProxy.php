<?php
	namespace tutomvc;

	class NotificationProxy extends Proxy
	{
		function onRegister()
		{
			if ( !session_id() )
			{
				session_name( SystemConstants::SESSION_NAME );
				session_start();
			}

			if ( array_key_exists( SystemConstants::SESSION_COOKIE_NOTIFICATIONS, $_SESSION ) )
			{
				foreach ( $_SESSION[ SystemConstants::SESSION_COOKIE_NOTIFICATIONS ] as $key => $notification )
				{
					$this->add( $notification->getValue(), $notification->getName() );
				}

				unset($_SESSION[ SystemConstants::SESSION_COOKIE_NOTIFICATIONS ]);
			}

			$this->getFacade()->controller->registerCommand( new AdminNoticesCommand() );

			add_action( Notification::ACTION_ADD_NOTIFICATION, array($this, "add"), 0, 2 );
			add_action( Notification::ACTION_ADD_NOTIFICATION_AS_SESSION_COOKIE, array($this, "addAsSessionCookie"), 0, 2 );
		}

		public function add( $message, $type = Notification::TYPE_NOTICE )
		{
			$notification = is_a( $message, "\\tutomvc\\Notification" ) ? $message : new Notification( $message, $type );

			if ( !AdminNoticesCommand::hasExecuted() )
			{
				return parent::add( $notification );
			}
			else
			{
				return $this->addAsSessionCookie( $notification );
			}
		}

		public function addAsSessionCookie( $message, $type = Notification::TYPE_NOTICE )
		{
			$notification = is_a( $message, "\\tutomvc\\Notification" ) ? $message : new Notification( $message, $type );

			if ( array_key_exists( SystemConstants::SESSION_COOKIE_NOTIFICATIONS, $_SESSION ) ) array_push( $_SESSION[ SystemConstants::SESSION_COOKIE_NOTIFICATIONS ], $notification );
			else $_SESSION[ SystemConstants::SESSION_COOKIE_NOTIFICATIONS ] = array(0 => $notification);

			return $notification;
		}
	}