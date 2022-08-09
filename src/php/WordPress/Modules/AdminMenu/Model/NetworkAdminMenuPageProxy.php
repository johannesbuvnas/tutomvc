<?php

	namespace TutoMVC\WordPress\Modules\AdminMenu\Model;


	use TutoMVC\WordPress\Core\Model\Proxy\Proxy;

	class NetworkAdminMenuPageProxy extends Proxy
	{
		const NAME = "tutomvc_network_admin_menu_page_proxy";

		function __construct()
		{
			parent::__construct( self::NAME );
		}
	}
