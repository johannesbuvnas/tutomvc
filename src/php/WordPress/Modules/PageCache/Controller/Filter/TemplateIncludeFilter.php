<?php

	namespace TutoMVC\WordPress\Modules\PageCache\Controller\Filter;

	use TutoMVC\WordPress\Core\Controller\Command\FilterCommand;
	use TutoMVC\WordPress\Modules\PageCache\PageCacheModule;

	class TemplateIncludeFilter extends FilterCommand
	{

		public function execute()
		{
			$template = func_get_arg( 0 );

			PageCacheModule::setOriginalTemplateInclude( $template );

			return PageCacheModule::getCacheTemplatePath();
		}
	}
