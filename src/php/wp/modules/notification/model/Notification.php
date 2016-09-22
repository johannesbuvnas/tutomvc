<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/11/15
	 * Time: 18:04
	 */

	namespace tutomvc\wp\notification;

	use tutomvc\core\model\ValueObject;

	class Notification extends ValueObject
	{
		function __construct( $content, $type = NotificationModule::TYPE_UPDATE )
		{
			parent::__construct( $type, $content );
		}
	}
