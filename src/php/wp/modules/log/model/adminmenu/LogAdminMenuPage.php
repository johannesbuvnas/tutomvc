<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 2018-01-19
	 * Time: 09:42
	 */

	namespace tutomvc\wp\log\model\adminmenu;

	use tutomvc\wp\adminmenu\model\AdminMenuPage;
	use tutomvc\wp\log\LogModule;

	class LogAdminMenuPage extends AdminMenuPage
	{
		const NAME = "tutomvc-logs";

		function __construct( $parentSlug )
		{
			parent::__construct( "Logs", "Logs", "manage_options", self::NAME, $parentSlug );
		}

		function render()
		{
			$view = LogModule::getModuleRoot( "templates/logs-admin-menu-page.php" );
			include($view);
		}
	}