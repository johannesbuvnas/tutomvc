<?php

	namespace tutomvc\wp\pagecache\controller\filters;

	use tutomvc\wp\pagecache\PageCacheModule;
	use tutomvc\wp\core\controller\command\FilterCommand;

	class TemplateIncludeFilter extends FilterCommand
	{

		public function onRegister()
		{

		}

		public function execute()
		{
			$template = func_get_arg( 0 );

			PageCacheModule::setOriginalTemplateInclude( $template );

			return PageCacheModule::getCacheTemplatePath();
		}
	}