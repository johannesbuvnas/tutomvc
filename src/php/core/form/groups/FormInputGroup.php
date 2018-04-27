<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/01/15
	 * Time: 20:18
	 */

	namespace tutomvc\core\form\groups;

	use tutomvc\core\form\FormElement;
	use tutomvc\core\form\inputs\FormInput;
	use tutomvc\core\form\inputs\FormInputAddon;

	/**
	 * A group of inputs.
	 *
	 * @package tutomvc\core\form\groups
	 * @see http://getbootstrap.com/components/#input-groups
	 */
	class FormInputGroup extends FormGroup
	{
		/**
		 * @param FormElement|FormInputAddon $formElement
		 *
		 * @throws \ErrorException
		 * @return FormElement
		 */
		public function add( FormElement $formElement )
		{
			if ( !($formElement instanceof FormInput) && !($formElement instanceof FormInputAddon) )
			{
				throw new \ErrorException( "Only accepts FormInput and FormInputAddon - ", 0, E_ERROR );
			}

			return parent::add( $formElement );
		}

		/**
		 * @return string The <input/> element
		 */
		function formatFormElementOutput()
		{
			$output = '<div class="input-group">';
			/** @var FormElement $formElement */
			foreach ( $this->getMap() as $formElement )
			{
				$output .= $formElement->formatFormElementOutput();
			}
			$output .= '</div>';

			return $output;
		}

		public function formatHeaderOutput()
		{
			return '<label class="control-label">' . $this->getLabel() . '</label>';
		}

		public function formatFooterOutput()
		{
			$desc = $this->getDescription();

			return is_string( $desc ) && strlen( $desc ) ? '<span class="help-block">' . $this->getDescription() . '</span>' : '';
		}

		/**
		 * Indexes not supported
		 *
		 * @param int|null $index
		 */
		public function setIndex( $index )
		{
			$index = NULL;
			parent::setIndex( $index );
		}

		public function hasError()
		{
			/** @var FormElement $formElement */
			foreach ( $this->getMap() as $formElement )
			{
				if ( $formElement->hasError() ) return TRUE;
			}

			return FALSE;
		}
	}