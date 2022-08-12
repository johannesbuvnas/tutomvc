<?php

	namespace TutoMVC\WordPress\Modules\PageCache\Controller\Action;

	use TutoMVC\WordPress\Core\Controller\Command\ActionCommand;
	use TutoMVC\WordPress\Modules\PageCache\PageCacheModule;
	use function wp_verify_nonce;

	class ClearPageCacheAction extends ActionCommand
	{
		const NAME = "tutomvc_clear_page_cache";

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
