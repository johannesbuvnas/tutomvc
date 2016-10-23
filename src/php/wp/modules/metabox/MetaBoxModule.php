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
		 *
		 * @return mixed|void
		 */
		public static function apply_filters( $value, $formElement )
		{
			return apply_filters( self::constructFilterHookName( $formElement ), $value, $formElement );
		}

		/**
		 * @param FormElement $formElement
		 *
		 * @return string
		 */
		public static function constructFilterHookName( $formElement )
		{
			return FormElement::sanitizeID( get_class( $formElement ) ) . "_meta_value";
		}

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
			self::getProxy()->add( $metaBox, $metaBox->getName() );

			return self::getInstance();
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

		/**
		 * @param $name
		 *
		 * @return MetaBox|null
		 */
		public static function getMetaBox( $name )
		{
			return self::getProxy()->get( $name );
		}
	}