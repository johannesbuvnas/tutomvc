<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/01/15
	 * Time: 20:57
	 */

	namespace tutomvc;

	class WPEditorFormInput extends TextAreaFormInput
	{
		function getFormElement()
		{
			$output = "";

			$attr = array(
				"placeholder" => $this->getPlaceholder(),
				"name"        => $this->getElementName(),
				"id"          => $this->getID(),
				"class"       => "form-control tutomvc-wp-editor hidden"
			);
			if ( $this->isReadOnly() ) $attr[ "readonly" ] = "true";

			$attributes = "";
			foreach ( $attr as $key => $value )
			{
				$attributes .= ' ' . $key . '="' . $value . '"';
			}

			$output .= '<textarea ' . $attributes . '>' . $this->getValue() . '</textarea>';

			return $output;
		}
	}