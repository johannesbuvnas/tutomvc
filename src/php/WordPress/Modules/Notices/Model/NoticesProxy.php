<?php

	namespace TutoMVC\WordPress\Modules\Notices\Model;

	use TutoMVC\WordPress\Core\Model\Proxy\Proxy;
	use TutoMVC\WordPress\Modules\Notices\Controller\Action\AdminNoticesAction;
	use function function_exists;
	use function gzdeflate;
	use function gzinflate;
	use function json_decode;
	use function json_encode;
	use function serialize;
	use function stripslashes;
	use function time;
	use function unserialize;
	use function urldecode;
	use function var_dump;

	class NoticesProxy extends Proxy
	{
		const NAME          = __CLASS__;
		const COOKIE_COUNT  = "tutomvc_notices_count";
		const COOKIE_NOTICE = "tutomvc_notice_";

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

		public function addAsSessionCookie( Notice $notification ): Notice
		{
			$cookieCount = $this->getCookieCount();
			$cookieCount ++;
			$noticeAsJSON = json_encode( array(
				"type"    => $notification->getName(),
				"content" => $notification->getValue()
			) );
			setcookie( self::COOKIE_NOTICE . $cookieCount, $noticeAsJSON, 0, "/", "", FALSE, TRUE );
			$this->setCookieCount( $cookieCount );

			return $notification;
		}

		public function getCookieCount(): int
		{
			if ( isset( $_COOKIE[ self::COOKIE_COUNT ] ) )
			{
				return intval( $_COOKIE[ self::COOKIE_COUNT ] );
			}

			return 0;
		}

		public function setCookieCount( int $count )
		{
			if ( $count )
			{
				setcookie( self::COOKIE_COUNT, $count, 0, "/", "", FALSE, TRUE );
			}
			else
			{
				unset( $_COOKIE[ self::COOKIE_COUNT ] );
				setcookie( self::COOKIE_COUNT, "", time() - 3600, "/", "", FALSE, TRUE );
			}
		}

		/* EVENTS */
		function onRegister()
		{
			if ( $this->getCookieCount() )
			{
				for ( $i = 1; $i <= $this->getCookieCount(); $i ++ )
				{
					$cookieName = self::COOKIE_NOTICE . $i;
//					var_dump($cookieName,isset( $_COOKIE[ $cookieName ] ));
					if ( isset( $_COOKIE[ $cookieName ] ) )
					{
						$noticeAsJSON = $_COOKIE[ $cookieName ];
						$noticeAsJSON = stripslashes( $noticeAsJSON );
						$noticeAsJSON = json_decode( $noticeAsJSON, TRUE );
						$notice       = new Notice( $noticeAsJSON[ 'content' ], $noticeAsJSON[ 'type' ] );
						$this->add( $notice );
					}

					unset( $_COOKIE[ $cookieName ] );
					setcookie( $cookieName, "", time() - 3600, "/", "", FALSE, TRUE );
				}
				$this->setCookieCount( 0 );
			}
		}
	}
