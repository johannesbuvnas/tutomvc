<?php

	namespace TutoMVC\WordPress\Modules\PageCache;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\Modules\AdminMenu\AdminMenuModule;
	use TutoMVC\WordPress\Modules\AdminMenu\Controller\AdminMenuPage;
	use TutoMVC\WordPress\Modules\PageCache\Controller\Action\ClearPageCacheAction;
	use TutoMVC\WordPress\Modules\PageCache\Controller\Action\SetupAdvancedCacheAction;
	use TutoMVC\WordPress\Modules\PageCache\Controller\Filter\TemplateIncludeFilter;
	use TutoMVC\WordPress\Modules\PageCache\Controller\PageCacheAdminMenuPage;
	use TutoMVC\WordPress\Modules\PageCache\Model\PageCacheProxy;
	use TutoMVC\WordPress\Modules\PageCache\PageCacheModule;
	use function array_key_exists;
	use function do_action;
	use function is_network_admin;
	use function wp_verify_nonce;

	class PageCacheFacade extends Facade
	{
		const KEY = "tutomvc_pagecache";

		public function __construct()
		{
			parent::__construct( self::KEY );
		}

		protected function prepModel()
		{
			PageCacheModule::initialize();

			$this->registerProxy( new PageCacheProxy() );

			if ( is_admin() )
			{
				if ( is_network_admin() ) AdminMenuModule::addPageToNetwork( new PageCacheAdminMenuPage( AdminMenuPage::PARENT_SLUG_NETWORK_SETTINGS ) );
				else AdminMenuModule::addPage( new PageCacheAdminMenuPage( AdminMenuPage::PARENT_SLUG_SETTINGS ) );
			}
		}

		protected function prepController()
		{
			if ( defined( "TUTOMVC_ADVANCED_CACHE" ) ) $this->registerCommand( "template_include", new TemplateIncludeFilter( 0 ) );

			if ( is_admin() )
			{
				$this->registerCommand( SetupAdvancedCacheAction::NAME, new SetupAdvancedCacheAction() );
				$this->registerCommand( ClearPageCacheAction::NAME, new ClearPageCacheAction() );
			}

			if ( !empty( $_POST ) && array_key_exists( "_wpnonce", $_POST ) )
			{
				if ( wp_verify_nonce( $_POST[ '_wpnonce' ], SetupAdvancedCacheAction::NAME ) )
				{
					do_action( SetupAdvancedCacheAction::NAME );
				}
				if ( wp_verify_nonce( $_POST[ '_wpnonce' ], ClearPageCacheAction::NAME ) )
				{
					do_action( ClearPageCacheAction::NAME );
				}
			}
		}

		/* SET AND GET */
		/**
		 * @return PageCacheProxy
		 */
		public function getPageCacheProxy()
		{
			return $this->getProxy( PageCacheProxy::NAME );
		}
	}
