<?php

	namespace TutoMVC\WordPress\Modules\PageCache\Controller\Action;

	use TutoMVC\Utils\FileUtil;
	use TutoMVC\WordPress\Core\Controller\Command\ActionCommand;
	use TutoMVC\WordPress\Modules\Notices\NoticesModule;
	use TutoMVC\WordPress\Modules\PageCache\PageCacheModule;
	use TutoMVC\WordPress\TutoMVC;
	use function wp_verify_nonce;

	class SetupAdvancedCacheAction extends ActionCommand
	{
		const NAME = "tutomvc_init_advanced_cache";

		function execute()
		{
			$php      = file_get_contents( PageCacheModule::getModuleRoot( "templates/advanced-cache.php" ) );
			$php      = str_ireplace( "[URLUtil.php]", TutoMVC::getRoot( "src/php/core/utils/URLUtil.php" ), $php );
			$php      = str_ireplace( "[FileUtil.php]", TutoMVC::getRoot( "src/php/core/utils/FileUtil.php" ), $php );
			$php      = str_ireplace( "[PageCacheModule.php]", PageCacheModule::getModuleRoot( "PageCacheModule.php" ), $php );
			$filePath = FileUtil::sanitizePath( WP_CONTENT_DIR . "/advanced-cache.php" );
			$result   = file_put_contents( $filePath, $php );
			if ( $result === FALSE )
			{
				NoticesModule::add( "Failed to setup <a href='#advanced-cache'>advanced-cache.php</a>.", NoticesModule::TYPE_ERROR );
			}
			else
			{
				NoticesModule::add( "<a href='#advanced-cache'><samp>advanced-cache.php</samp></a> has been setup!" );
			}
		}
	}
