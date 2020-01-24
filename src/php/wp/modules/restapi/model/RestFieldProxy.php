<?php

	namespace tutomvc\wp\restapi;

	use tutomvc\wp\core\model\proxy\Proxy;

	class RestFieldProxy extends Proxy
	{
		const NAME = __CLASS__;

		function __construct()
		{
			parent::__construct( self::NAME );
		}
	}