<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 2018-01-19
	 * Time: 09:42
	 */

	namespace TutoMVC\WordPress\Modules\Log\Controller;

	use TutoMVC\WordPress\Modules\AdminMenu\Controller\AdminMenuPage;
	use TutoMVC\WordPress\Modules\Log\LogModule;

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

		function load()
		{
		}
	}
