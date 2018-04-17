<?php

	namespace tutomvc\wp\pagecache\controller\actions;

	use tutomvc\core\utils\FileUtil;
	use tutomvc\wp\core\controller\command\ActionCommand;
	use tutomvc\wp\notification\NotificationModule;
	use tutomvc\wp\pagecache\PageCacheModule;
	use tutomvc\wp\TutoMVC;

	class SetupAdvancedCacheAction extends ActionCommand
	{
		const NAME = "tutomvc_init_advanced_cache";

		public function onRegister()
		{
			if ( !empty( $_POST ) && array_key_exists( "_wpnonce", $_POST ) && wp_verify_nonce( $_POST[ '_wpnonce' ], self::NAME ) )
			{
				$this->execute();
			}
		}

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
				NotificationModule::add( "Failed to setup <a href='#advanced-cache'>advanced-cache.php</a>.", NotificationModule::TYPE_ERROR );
			}
			else
			{
				NotificationModule::add( "<a href='#advanced-cache'><samp>advanced-cache.php</samp></a> has been setup!" );
			}
		}
	}