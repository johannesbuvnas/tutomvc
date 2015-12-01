<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/04/15
	 * Time: 13:37
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\Facade;
	use tutomvc\FormElement;
	use tutomvc\SystemApp;

	class MetaBoxModule
	{

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
			self::getProxy()->add( $metaBox );

			return self::getInstance();
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