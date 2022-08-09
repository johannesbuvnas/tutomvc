<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 2016-10-14
	 * Time: 09:59
	 */

	namespace TutoMVC\Form\Input;

	use TutoMVC\Form\Input\FormInput;

	class CheckBoxFormInput extends FormInput
	{
		function __construct( $name, $title, $description = NULL, $readonly = FALSE )
		{
			parent::__construct( $name, $title, $description, self::TYPE_CHECKBOX, $readonly, NULL, TRUE );
		}

		function getFormElementAttributes()
		{
			$attr = parent::getFormElementAttributes();
			if ( array_key_exists( "value", $attr ) ) unset($attr[ "value" ]);
			$attr[ "class" ] = "form-input-element";

			return $attr;
		}
	}
