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

		\tutomvc\wp\pagecache\PageCacheModule::initialize();

		if ( \tutomvc\wp\pagecache\PageCacheModule::isIgnoreCacheMode() || \tutomvc\wp\pagecache\PageCacheModule::hasExpired() ) return FALSE;

		\tutomvc\wp\pagecache\PageCacheModule::renderPageCache();
	}