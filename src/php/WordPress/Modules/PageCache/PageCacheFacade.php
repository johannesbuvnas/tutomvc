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
	use function is_network_admin;

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
				$this->registerCommand( SetupAdvancedCacheAction::NAME, new ClearPageCacheAction() );
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
