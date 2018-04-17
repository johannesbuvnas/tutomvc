<?php

	namespace tutomvc\wp\pagecache;

	use tutomvc\wp\adminmenu\AdminMenuModule;
	use tutomvc\wp\adminmenu\model\AdminMenuPage;
	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\pagecache\controller\actions\ClearPageCacheAction;
	use tutomvc\wp\pagecache\controller\actions\SetupAdvancedCacheAction;
	use tutomvc\wp\pagecache\controller\filters\TemplateIncludeFilter;
	use tutomvc\wp\pagecache\model\adminmenu\PageCacheAdminMenuPage;
	use tutomvc\wp\pagecache\model\proxy\PageCacheProxy;

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