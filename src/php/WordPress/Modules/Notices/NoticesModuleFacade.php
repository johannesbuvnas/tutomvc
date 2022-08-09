<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/11/15
	 * Time: 17:40
	 */

	namespace TutoMVC\WordPress\Modules\Notices;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\Modules\Notices\Controller\Action\AdminNoticesAction;
	use TutoMVC\WordPress\Modules\Notices\Model\NoticesProxy;

	class NoticesModuleFacade extends Facade
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
			$this->registerProxy( new NoticesProxy() );
			// Controller
			$this->registerCommand( "admin_notices", new AdminNoticesAction() );
		}
	}
