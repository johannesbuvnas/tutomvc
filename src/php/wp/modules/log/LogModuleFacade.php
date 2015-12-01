<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 19/11/15
	 * Time: 08:19
	 */
	namespace tutomvc\wp\log;

	class LogModuleFacade extends \tutomvc\Facade
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