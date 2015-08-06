<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/01/15
	 * Time: 20:18
	 */

	namespace tutomvc;

	class FormInputGroup extends FormGroup
	{
		/**
		 * @param FormElement|FormInputAddon $formElement
		 *
		 * @throws \ErrorException
		 * @return FormElement
		 */
		public function addFormElement( $formElement )
		{
			if ( !is_a( $formElement, "\\tutomvc\\FormInput" ) && !is_a( $formElement, "\\tutomvc\\FormInputAddon" ) )
			{
				throw new \ErrorException( "Only accepts FormInput and FormInputAddon - ", 0, E_ERROR );
			}

			return parent::addFormElement( $formElement );
		}

		/**
		 * @return string The <input/> element
		 */
		function getFormElement()
		{
			$output = '<div class="input-group">';
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				$output .= $formElement->getFormElement();
			}
			$output .= '</div>';

			return $output;
		}

		public function getHeaderElement()
		{
			return '<label class="control-label">' . $this->getLabel() . '</label>';
		}

		public function getFooterElement()
		{
			return '<span class="help-block">' . $this->getDescription() . '</span>';
		}

		public function getErrorMessageElement()
		{
			$output = '';

			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				$output .= $formElement->getErrorMessageElement();
			}

			return $output;
		}

		/**
		 * @return bool
		 */
		public function hasError()
		{
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( $formElement->hasError() ) return TRUE;
			}

			return FALSE;
		}
	}