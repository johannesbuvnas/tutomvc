<?php
	namespace tutomvc\wp\taxonomy;

	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\system\SystemApp;

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 10/05/15
	 * Time: 08:34
	 */
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