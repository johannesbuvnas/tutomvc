<?php

	namespace TutoMVC\WordPress\Modules\RestAPI;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\Modules\RestAPI\Controller\Action\RestAPIInitAction;
	use TutoMVC\WordPress\Modules\RestAPI\Model\RestFieldProxy;
	use TutoMVC\WordPress\Modules\RestAPI\Model\RestRouteProxy;

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
