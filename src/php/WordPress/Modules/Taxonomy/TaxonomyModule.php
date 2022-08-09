<?php
	namespace TutoMVC\WordPress\Modules\Taxonomy;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\System\SystemApp;
	use TutoMVC\WordPress\Modules\Taxonomy\TaxonomyModuleFacade;
	use TutoMVC\WordPress\Modules\Taxonomy\Model\TaxonomyProxy;

	class TaxonomyModule
	{
		/**
		 * @param $taxonomy
		 *
		 * @return TaxonomyModuleFacade
		 */
		public static function add( $taxonomy )
		{
			self::getProxy()->add( $taxonomy );

			return self::getInstance();
		}

		/**
		 * @return TaxonomyModuleFacade
		 */
		public static function getInstance()
		{
			return Facade::getInstance( TaxonomyModuleFacade::KEY ) ? Facade::getInstance( TaxonomyModuleFacade::KEY ) : SystemApp::getInstance()->registerModule( new TaxonomyModuleFacade() );
		}

		/**
		 * @return TaxonomyProxy
		 */
		public static function getProxy()
		{
			return self::getInstance()->getProxy( TaxonomyProxy::NAME );
		}
	}
