<?php

	namespace tutomvc\wp\pagecache\controller\actions;

	use tutomvc\wp\core\controller\command\ActionCommand;
	use tutomvc\wp\pagecache\PageCacheModule;

	class ClearPageCacheAction extends ActionCommand
	{
		const NAME = "tutomvc_clear_page_cache";

		public function onRegister()
		{
			if ( !empty( $_POST ) && array_key_exists( "_wpnonce", $_POST ) && wp_verify_nonce( $_POST[ '_wpnonce' ], self::NAME ) ) $this->execute();
		}

		public function execute()
		{
			$what = array_key_exists( "what", $_POST ) ? $_POST[ 'what' ] : "everygadamthing";

			switch ( $what )
			{
				case "everygadamthing":

					PageCacheModule::clearByURI( "/", TRUE );

					break;
				case "site":

					$siteURL = get_bloginfo( "siteurl" );
					$siteURL = parse_url( $siteURL );

					PageCacheModule::clearByURI( $siteURL[ 'host' ], TRUE );

					break;
				default:

					PageCacheModule::clearByURI( $what );

					break;
			}
		}
	}