<?php
	namespace tutomvc\wp\taxonomy;

	use tutomvc\Facade;

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
			return Facade::getInstance( TaxonomyModuleFacade::KEY ) ? Facade::getInstance( TaxonomyModuleFacade::KEY ) : Facade::getInstance( Facade::KEY_SYSTEM )->registerSubFacade( new TaxonomyModuleFacade() );
		}

		/**
		 * @return TaxonomyProxy
		 */
		public static function getProxy()
		{
			return self::getInstance()->getProxy();
		}
	}