<?php

	namespace TutoMVC\WordPress\Modules\PageCache\Controller;

	use TutoMVC\WordPress\Modules\PageCache\PageCacheModule;
	use TutoMVC\WordPress\Modules\AdminMenu\Controller\AdminMenuPage;

	class PageCacheAdminMenuPage extends AdminMenuPage
	{
		const SLUG = "tutomvc-view-cache";

		function __construct( $subpage )
		{
			parent::__construct( "Page Cache", "Page Cache", "manage_options", self::SLUG, $subpage );
		}

		function render()
		{
			$view = PageCacheModule::getModuleRoot( "templates/view/admin-menu-page-main.php" );
			include($view);
		}
	}
