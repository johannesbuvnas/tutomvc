<?php

	namespace TutoMVC\WordPress\Modules\Log;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\Modules\Log\Controller\Ajax\GetLogAjaxCommand;
	use TutoMVC\WordPress\Modules\Log\Model\LogProxy;

	class LogModuleFacade extends Facade
	{
		const KEY = "com.tutomvc.wp.modules.log";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		/* EVENTS */
		function onRegister()
		{
			// Model
			$this->registerProxy( new LogProxy() );
			// Controller
			$this->registerCommand( GetLogAjaxCommand::NAME, new GetLogAjaxCommand() );
		}
	}
