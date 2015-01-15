<?php
	namespace tutomvc;

	class NotificationMediator extends Mediator
	{
		const NAME = "util/notification.php";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function setNotification( $value )
		{
			$this->parse( "notification", $value );

			return $this;
		}

		function getNotification()
		{
			return $this->retrieve( "notification" );
		}
	}