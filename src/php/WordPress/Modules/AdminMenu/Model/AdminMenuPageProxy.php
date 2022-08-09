<?php
	namespace TutoMVC\WordPress\Modules\AdminMenu\Model;


	use TutoMVC\WordPress\Core\Model\Proxy\Proxy;

	class AdminMenuPageProxy extends Proxy
	{

		const NAME = __CLASS__;

		function __construct()
		{
			parent::__construct( self::NAME );
		}
	}
