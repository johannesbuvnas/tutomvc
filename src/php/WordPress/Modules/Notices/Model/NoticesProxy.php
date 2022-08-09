<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/11/15
	 * Time: 17:44
	 */

	namespace TutoMVC\WordPress\Modules\Notices\Model;

	use TutoMVC\WordPress\Core\Model\Proxy\Proxy;
	use TutoMVC\WordPress\Modules\Notices\NoticesModule;
	use TutoMVC\WordPress\Modules\Notices\Controller\Action\AdminNoticesAction;

	class NoticesProxy extends Proxy
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
		 * @param Notice $notification
		 *
		 * @return mixed
		 */
		public function addAsSessionCookie( $notification )
		{
			if ( array_key_exists( NoticesModule::SESSION_COOKIE, $_SESSION ) ) array_push( $_SESSION[ NoticesModule::SESSION_COOKIE ], $notification );
			else $_SESSION[ NoticesModule::SESSION_COOKIE ] = array(0 => $notification);

			return $notification;
		}

		/* EVENTS */
		function onRegister()
		{
			if(!$_SESSION) $_SESSION = array();
			
			if ( array_key_exists( NoticesModule::SESSION_COOKIE, $_SESSION ) )
			{
				foreach ( $_SESSION[ NoticesModule::SESSION_COOKIE ] as $key => $notification )
				{
					$this->add( $notification );
				}

				unset( $_SESSION[ NoticesModule::SESSION_COOKIE ]);
			}
		}
	}
