<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/11/15
	 * Time: 17:40
	 */

	namespace tutomvc\wp\notification;

	use tutomvc\Facade;
	use tutomvc\SystemFacade;

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
			$this->model->registerProxy( new NotificationProxy() );
			// Controller
			$this->controller->registerCommand( new AdminNoticesAction() );
		}
	}