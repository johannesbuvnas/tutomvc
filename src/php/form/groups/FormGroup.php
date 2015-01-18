<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 09:13
	 */

	namespace tutomvc;

	class FormGroup extends FormElement
	{
		private $_fieldMap = array();

		function __construct( $name, $title = NULL, $description = NULL )
		{
			parent::__construct( $name, NULL );
			$this->setLabel( $title );
			$this->setDescription( $description );
		}

		public function getHeaderElement()
		{
			return '
					<header>
						<h3>
							' . $this->getLabel() . '
							<small class="help-block">
								' . $this->getDescription() . '
							</small>
						</h3>
					</header>
			';
		}

		public function getFooterElement()
		{
			return '<hr/>';
		}

		function getFormElement()
		{
			$output = '<div class="form-group" id="' . $this->getID() . '">';
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				$originalName = $formElement->getName();
				$formElement->setID( $this->constructInputID( $originalName ) );
				$output .= '<div class="form-group form-group-input">';
				$output .= $formElement->getHeaderElement();
				$formElement->setName( $this->constructFormElementChildName( $originalName ) );
				$output .= $formElement->getFormElement();
				$formElement->setName( $originalName );
				$output .= $formElement->getFooterElement();
				$output .= '</div>';
			}
			$output .= '</div>';

			return $output;
		}

		public function constructFormElementChildName( $formElementName )
		{
			return !$this->isSingle() ? $this->getName() . "[" . $this->getIndex() . "][" . $formElementName . "]" : $this->getName() . "[" . $formElementName . "]";
		}

		public function constructInputID( $formElementName )
		{
			$name = $this->constructFormElementChildName( $formElementName );
			$name = preg_replace( "/\]/", "", $name );
			$name = preg_replace( "/\[/", "_", $name );

			return $name;
		}

		/**
		 * @param FormElement $formElement
		 *
		 * @return FormElement
		 */
		public function addFormElement( FormElement $formElement )
		{
			$this->_fieldMap[ $formElement->getName() ] = $formElement;

			return $formElement;
		}

		/* SET AND GET */
		public function getFormElements()
		{
			return $this->_fieldMap;
		}

		/**
		 * @param $name
		 *
		 * @return null|FormElement
		 */
		public function getFormElementByName( $name )
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

		/**
		 * @param array|null $value
		 */
		public function setValue( $value )
		{
			if ( !is_array( $value ) && !is_null( $value ) )
			{
				throw new \ErrorException( "Expect array or null.", 0, E_ERROR );
			}

			if ( is_array( $value ) )
			{
				foreach ( $value as $key => $value )
				{
					if ( $this->getFormElementByName( $key ) )
					{
						$this->getFormElementByName( $key )->setValue( $value );
					}
				}
			}
			else if ( is_null( $value ) )
			{
				foreach ( $this->getFormElements() as $formElement )
				{
					$formElement->setValue( NULL );
				}
			}

			return $this;
		}

		/**
		 * @return array|null
		 */
		public function getValue()
		{
			$value = array();
			foreach ( $this->getFormElements() as $formInput )
			{
				$value[ $formInput->getName() ] = $formInput->getValue();
			}

			return $value;
		}
	}