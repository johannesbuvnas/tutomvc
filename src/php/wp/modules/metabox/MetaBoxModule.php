<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/04/15
	 * Time: 13:37
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\Facade;

	class MetaBoxModule
	{
		/**
		 *
		 * @see https://codex.wordpress.org/Function_Reference/add_meta_box
		 *
		 * @param MetaBox $metaBox
		 *
		 * @return Facade|MetaBoxModuleFacade
		 */
		public static function add( $metaBox )
		{
			self::getProxy()->add( $metaBox );

			return self::getInstance();
		}

		/**
		 * @param int|string $postID
		 * @param string $metaKey
		 * @param bool $suppressFilters
		 *
		 * @return array|mixed|null|void
		 * @throws \ErrorException
		 */
		public static function getPostMeta( $postID, $metaKey, $suppressFilters = FALSE )
		{
			return self::getProxy()->getPostMeta( $postID, $metaKey, $suppressFilters );
		}

		/**
		 * @return Facade|MetaBoxModuleFacade
		 */
		public static function getInstance()
		{
			return Facade::getInstance( MetaBoxModuleFacade::KEY ) ? Facade::getInstance( MetaBoxModuleFacade::KEY ) : Facade::getInstance( Facade::KEY_SYSTEM )->registerSubFacade( new MetaBoxModuleFacade() );
		}

		/**
		 * @return null|MetaBoxProxy
		 */
		public static function getProxy()
		{
			return self::getInstance()->getProxy();
		}
	}