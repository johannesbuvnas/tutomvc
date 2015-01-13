<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 09:13
	 */

	namespace tutomvc;

	class FormFieldGroup extends FormInput
	{
		private $_fieldMap = array();

		function __construct( $name )
		{
			parent::__construct( $name, NULL );
		}

		function getFormElement()
		{
			$output = '<div class="form-group">';
			foreach($this->getFields() as $formField)
			{
				$output .= '<div class="form-group">';
//				$output .= '<header>';
				$output .= $formField->getLabelElement();
//				$output .= '</header>';
				$output .= $formField->getFormElement();
				$output .= $formField->getDescriptionElement();
				$output .= '</div>';
			}
			$output .= '</div>';
		}

		public function addField( FormField $formField )
		{
			$this->_fieldMap[ $formField->getName() ] = $formField;

			return $formField;
		}

		public function hasField( $name )
		{
			return array_key_exists( $name, $this->_fieldMap );
		}

		/* SET AND GET */
		public function getFields()
		{
			return $this->_fieldMap;
		}

		public function getField( $name )
		{
			if ( array_key_exists( $name, $this->_fieldMap ) )
			{
				return $this->_fieldMap[ $name ];
			}
			else
			{
				return NULL;
			}
		}
	}