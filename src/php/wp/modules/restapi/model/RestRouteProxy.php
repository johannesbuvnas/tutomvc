<?php

	namespace tutomvc\wp\restapi;

	use tutomvc\wp\core\model\proxy\Proxy;

	class RestRouteProxy extends Proxy
	{
		const NAME = __CLASS__;

		public function __construct()
		{
			parent::__construct( self::NAME );
		}
	}