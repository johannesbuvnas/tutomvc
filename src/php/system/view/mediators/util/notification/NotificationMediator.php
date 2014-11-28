<?php
namespace tutomvc;

class NotificationMediator extends Mediator
{
	const NAME = "util/notification.php";

	protected $_notification;

	function __construct()
	{
		parent::__construct( self::NAME );
	}


	function getContent()
	{
		$this->parse( "notification", $this->getNotification() );

		return parent::getContent();
	}

	function setNotification( $value )
	{
		$this->_notification = $value;

		return $this;
	}
	function getNotification()
	{
		return $this->_notification;
	}
}