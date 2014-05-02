<?php
namespace tutomvc;

class Notification extends ValueObject
{

	const TYPE_NOTICE = "updated";
	const TYPE_ERROR = "error";
	const TYPE_UPDATE = "update-nag";


	function __construct( $message, $type = Notification::TYPE_NOTICE )
	{
		parent::__construct( $type, $message );
	}
}
