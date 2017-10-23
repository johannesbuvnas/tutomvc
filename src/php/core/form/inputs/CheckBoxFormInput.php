<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 2016-10-14
	 * Time: 09:59
	 */

	namespace tutomvc\core\form\inputs;

	class CheckBoxFormInput extends FormInput
	{
		function __construct( $name, $title, $description = NULL, $readonly = FALSE )
		{
			parent::__construct( $name, $title, $description, self::TYPE_CHECKBOX, $readonly, NULL, TRUE );
		}

		public function formatOutput()
		{
			$classNames = array(
				"form-group",
				self::CSS_CLASS,
			);
			if ( is_string( $this->getErrorMessage() ) ) $classNames[] = "has-error";
			if ( $this->getType() == self::TYPE_HIDDEN ) $classNames[] = "hidden";
			$output = '<div class="' . implode( " ", $classNames ) . '">';
			$output .= $this->formatHeaderOutput();
			$output .= $this->formatErrorMessageOutput();
			$output .= $this->formatFooterOutput();
			$output .= '</div>';

			return $output;
		}

		function getFormElementAttributes()
		{
			$attr = parent::getFormElementAttributes();
			if ( array_key_exists( "value", $attr ) ) unset($attr[ "value" ]);
			$attr[ "class" ] = "form-input-element";

			return $attr;
		}

		public function formatHeaderOutput()
		{
			return '<div class="checkbox"><label class="control-label" for="' . $this->getID() . '">' . $this->formatFormElementOutput() . " " . $this->getLabel() . '</label></div>';
		}
	}