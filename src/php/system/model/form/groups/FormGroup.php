<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 09:13
	 */

	namespace tutomvc;

	class FormGroup extends Form
	{
		private $_fieldMap = array();

		function __construct( $name )
		{
			parent::__construct( $name, NULL );
		}

		function getFormElement()
		{
			$output = '<div class="form-group">';
			foreach ( $this->getInputs() as $formInput )
			{
//				$output .= '<div class="form-group">';
//				$output .= '<header>';
				$output .= $formInput->getLabelElement();
//				$output .= $formInput->getDescriptionElement();
//				$output .= '</header>';
				$output .= $formInput->getFormElement();
				$output .= $formInput->getDescriptionElement();
//				$output .= '</div>';
			}
			$output .= '</div>';

			return $output;
		}

		public function addInput( FormInput $formField )
		{
			$this->_fieldMap[ $formField->getName() ] = $formField;

			return $formField;
		}

		public function hasInput( $name )
		{
			return $this->getInputByName( $name ) ? $this->getInputByName( $name ) : FALSE;
		}

		/* SET AND GET */
		public function getInputs()
		{
			return $this->_fieldMap;
		}

		public function getInputByName( $name )
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