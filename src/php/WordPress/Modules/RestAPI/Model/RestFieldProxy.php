<?php

	namespace TutoMVC\WordPress\Modules\RestAPI\Model;


	use TutoMVC\WordPress\Core\Model\Proxy\Proxy;

	class RestFieldProxy extends Proxy
	{
		const NAME = __CLASS__;

		function __construct()
		{
			parent::__construct( self::NAME );
		}
	}
