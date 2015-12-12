<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/11/15
	 * Time: 17:40
	 */

	namespace tutomvc\wp\notification;

	use tutomvc\wp\Facade;

	class NotificationModuleFacade extends Facade
	{
		const KEY = "com.tutomvc.wp.modules.notifcation";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		/* EVENTS */
		function onRegister()
		{
			// Model
			$this->registerProxy( new NotificationProxy() );
			// Controller
			$this->registerCommand( "admin_notices", new AdminNoticesAction() );
		}
	}