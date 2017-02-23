<?php

	namespace tutomvc\wp\adminmenu\model;

	use tutomvc\wp\core\model\proxy\Proxy;

	class NetworkAdminMenuPageProxy extends Proxy
	{
		const NAME = "tutomvc_network_admin_menu_page_proxy";

		function __construct()
		{
			parent::__construct( self::NAME );
		}
	}