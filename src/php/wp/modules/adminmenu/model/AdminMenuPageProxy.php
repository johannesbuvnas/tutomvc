<?php
	namespace tutomvc\wp\adminmenu\model;

	use tutomvc\wp\core\model\proxy\Proxy;

	class AdminMenuPageProxy extends Proxy
	{

		const NAME = __CLASS__;

		function __construct()
		{
			parent::__construct( self::NAME );
		}
	}