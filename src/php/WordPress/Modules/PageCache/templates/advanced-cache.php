<?php
	if ( !defined( 'ABSPATH' ) )
	{
		die();
	}

	define( "TUTOMVC_ADVANCED_CACHE", TRUE );

	if ( defined( 'WP_INSTALLING' ) && WP_INSTALLING )
		return;

	if ( !is_admin() )
	{
		require_once("[URLUtil.php]");
		require_once("[FileUtil.php]");
		require_once("[PageCacheModule.php]");

		\TutoMVC\WordPress\Modules\PageCache\PageCacheModule::initialize();

		if ( \TutoMVC\WordPress\Modules\PageCache\PageCacheModule::isIgnoreCacheMode() || \TutoMVC\WordPress\Modules\PageCache\PageCacheModule::hasExpired() ) return FALSE;

		\TutoMVC\WordPress\Modules\PageCache\PageCacheModule::renderPageCache();
	}
