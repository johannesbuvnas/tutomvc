<?php

	namespace TutoMVC\WordPress\Modules\RestAPI\Model;


	use TutoMVC\WordPress\Core\Model\Proxy\Proxy;

	class RestRouteProxy extends Proxy
	{
		const NAME = __CLASS__;

		public function __construct()
		{
			parent::__construct( self::NAME );
		}
	}
