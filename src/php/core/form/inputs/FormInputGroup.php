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

		function getFormElement()
		{
			$output = '<div class="input-group">';
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				$formElement->setParentName( $this->getNameAsParent() );
//				$formElement->setID( $this->constructInputID( $originalName ) );
//				$formElement->setName( $this->constructFormElementChildName( $originalName ) );
				$output .= $formElement->getFormElement();
//				$formElement->setName( $originalName );
			}
			$output .= '</div>';

			return $output;
		}

		public function getHeaderElement()
		{
			return '<label>' . $this->getLabel() . '</label>';
		}

		public function getFooterElement()
		{
			return '<span class="help-block">' . $this->getDescription() . '</span>';
		}
	}