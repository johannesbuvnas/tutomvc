<?php

	namespace tutomvc\wp\restapi;

	use tutomvc\wp\core\facade\Facade;

	class RestAPIModuleFacade extends Facade
	{
		const KEY = "com.tutomvc.wp.modules.restapi";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		function prepModel()
		{
			$this->registerProxy( new RestFieldProxy() );
			$this->registerProxy( new RestRouteProxy() );
		}

		function prepController()
		{
			$this->registerCommand( "rest_api_init", new RestAPIInitAction() );
		}
	}