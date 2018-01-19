<?php

	namespace tutomvc\wp\log;

	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\log\actions\GetLogAjaxCommand;

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