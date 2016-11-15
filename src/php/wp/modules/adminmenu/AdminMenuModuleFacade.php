<?php

	namespace tutomvc\wp\adminmenu;

	use tutomvc\wp\adminmenu\model\AdminMenuPageProxy;
	use tutomvc\wp\core\facade\Facade;

	class AdminMenuModuleFacade extends Facade
	{
		const KEY = "com.tutomvc.wp.modules.adminmenu";

		function prepModel()
		{
			$this->registerProxy( new AdminMenuPageProxy() );
		}
	}