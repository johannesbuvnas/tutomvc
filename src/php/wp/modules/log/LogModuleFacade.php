<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 19/11/15
	 * Time: 08:19
	 */
	namespace tutomvc\wp\log;

	use tutomvc\wp\core\facade\Facade;

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
		}
	}