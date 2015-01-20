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

		protected function fixChildNames()
		{
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				$formElement->setParentName( $this->getNameAsParent() );
			}
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
//				$originalName = $formElement->getName();
//				$formElement->setID( $this->constructInputID( $originalName ) );
				$output .= '<div class="form-group form-group-input">';
//				$formElement->setParentName( $this->getNameAsParent() );
				$output .= $formElement->getHeaderElement();
//				$formElement->setName( $this->constructFormElementChildName( $originalName ) );
				$output .= $formElement->getFormElement();
//				$formElement->setName( $originalName );
				$output .= $formElement->getFooterElement();
				$output .= '</div>';
			}
			$output .= '</div>';

			return $output;
		}

		/**
		 * @param FormElement $formElement
		 *
		 * @return FormElement
		 */
		public function addFormElement( FormElement $formElement )
		{
			$this->_fieldMap[ $formElement->getName() ] = $formElement;
			$formElement->setParentName( $this->getNameAsParent() );

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
		 * @param $name
		 *
		 * @return null|FormElement
		 */
		public function getFormElementBySanitizedName( $sanitizedName )
		{
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( $formElement->getName() == $sanitizedName ) return $formElement;
			}

			return NULL;
		}

		public function getValueMapByElementName( $elementName )
		{
			$matches = FormElement::matchElementName( $elementName );

			if ( count( $matches ) == 4 )
			{
				$ancestor = $matches[ 1 ];
				if ( $ancestor == $this->getElementName() )
				{
					$index    = intval( $matches[ 2 ] );
					$rest     = $matches[ 3 ];
					$children = FormElement::extractGroupNames( $rest );
					if ( is_array( $children ) && count( $children ) )
					{
						$this->setIndex( $index );
						$formElement = $this->getFormElementByElementName( $elementName );
						if ( $formElement )
						{
							return $formElement->getValueMap();
						}
					}
					else
					{
						$this->setIndex( $index );

						return $this->getValueMap();
					}
				}
			}

			return FALSE;
		}

		public function getFormElementByElementName( $elementName )
		{
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( $formElement->getElementName() == $elementName ) return $formElement;

				if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
				{
					/** @var FormGroup $formElement */
					$search = $formElement->getFormElementByElementName( $elementName );
					if ( $search ) return $search;
				}
			}

			return NULL;
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
					$formElement = $this->getFormElementBySanitizedName( $key );
					if ( $formElement )
					{
						$formElement->setValue( $value );
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
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				$value[ $formElement->getName() ] = $formElement->getValue();
			}

			return $value;
		}

		/**
		 * @return array
		 */
		public function getFlatValue()
		{
			$value = array();
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
				{
					$value = array_merge( $value, $formElement->getFlatValue() );
				}
				else
				{
					$value[ $formElement->getElementName() ] = $formElement->getValue();
				}
			}

			return $value;
		}

		public function getValueMap()
		{
			$valueMap = array();

			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
				{
					if ( strlen( $formElement->getName() ) ) $valueMap[ $formElement->getName() ] = $formElement->getValueMap();
				}
				else
				{
					if ( strlen( $formElement->getName() ) ) $valueMap[ $formElement->getName() ] = $formElement->getValueMap();
				}
			}

			return $valueMap;
		}

		public function setName( $name )
		{
			parent::setName( $name );
			$this->fixChildNames();

			return $this;
		}

		public function setParentName( $parentName )
		{
			parent::setParentName( $parentName );

			$this->fixChildNames();

			return $this;
		}

		public function getNameAsParent()
		{
			$name = $this->hasParent() ? "[" . $this->getName() . "]" : $this->getName();

			return $this->_parentName . $name;
		}
	}