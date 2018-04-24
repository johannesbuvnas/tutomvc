<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/04/15
	 * Time: 13:37
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\core\form\FormElement;
	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\system\SystemApp;

	class MetaBoxModule
	{

		/**
		 * @param $value
		 * @param FormElement $formElement
		 * @param int $postID
		 *
		 * @return mixed|void
		 */
		public static function apply_filters( $value, $formElement, $postID )
		{
			return apply_filters( self::constructFilterHookName( $formElement ), $value, $formElement, $postID );
		}

		/**
		 * @param FormElement $formElement
		 *
		 * @return string
		 */
		public static function constructFilterHookName( $formElement )
		{
			return spl_object_hash( $formElement ) . "_meta_value";
		}

		/**
		 *
		 * @see https://codex.wordpress.org/Function_Reference/add_meta_box
		 *
		 * @param MetaBox $metaBox
		 */
		public static function add( $metaBox )
		{
			self::getProxy()->add( $metaBox, $metaBox->getName() );
		}

		/**
		 * @param $metaBoxName
		 *
		 * @return MetaBox|null
		 */
		public static function get( $metaBoxName )
		{
			return self::getProxy()->get( $metaBoxName );
		}

		public static function findMetaKey( $metaBoxName, $formElementName, $atIndex = 0 )
		{
			/** @var MetaBox $metaBox */
			if ( $metaBox = self::get( $metaBoxName ) )
			{
				$metaBox->setIndex( $atIndex );
				if ( $formElement = $metaBox->findByName( $formElementName ) )
				{
					return $formElement->getElementName();
				}
			}

			return NULL;
		}

		/**
		 * @param int $postID
		 * @param MetaBox $metaBox
		 * @param FormElement|null $formElement
		 * @param int|null $fissionIndex
		 *
		 * @return mixed
		 */
		public static function getWPPostMeta( $postID, $metaBox, $formElement = NULL, $fissionIndex = NULL )
		{
			return self::getProxy()->getPostMeta( $postID, $metaBox, $formElement, $fissionIndex );
		}

		/**
		 * @param int $postID
		 * @param string $metaBoxName
		 * @param null|string $formElementName
		 * @param null|int $fissionIndex
		 *
		 * @return bool|mixed
		 */
		public static function getWPPostMetaByName( $postID, $metaBoxName, $formElementName = NULL, $fissionIndex = NULL )
		{
			return self::getProxy()->getPostMetaByName( $postID, $metaBoxName, $formElementName, $fissionIndex );
		}

		/**
		 * @param $metaBoxName
		 *
		 * @return bool
		 */
		public static function remove( $metaBoxName )
		{
			return self::getProxy()->delete( $metaBoxName );
		}

		/**
		 * @return Facade|MetaBoxModuleFacade
		 */
		public static function getInstance()
		{
			return Facade::getInstance( MetaBoxModuleFacade::KEY ) ? Facade::getInstance( MetaBoxModuleFacade::KEY ) : SystemApp::getInstance()->registerModule( new MetaBoxModuleFacade() );
		}

		/**
		 * @return null|MetaBoxProxy
		 */
		public static function getProxy()
		{
			return self::getInstance()->getProxy( MetaBoxProxy::NAME );
		}
	}