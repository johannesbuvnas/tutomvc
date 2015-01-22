<?php
namespace tutomvc;

class Notification extends ValueObject
{
	const ACTION_ADD_NOTIFICATION = "model/proxy/NotifcationProxy/add";
	const ACTION_ADD_NOTIFICATION_AS_SESSION_COOKIE = "model/proxy/NotifcationProxy/addAsSessionCookie";
	const TYPE_NOTICE = "updated";
	const TYPE_ERROR = "error";
	const TYPE_UPDATE = "update-nag";


	function __construct( $message, $type = Notification::TYPE_NOTICE )
	{
		parent::__construct( $type, $message );
	}
}
