<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 09:13
	 */

	namespace tutomvc;

	/**
	 * Class FormGroup
	 * A group of FormElements
	 * @package tutomvc
	 */
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
				$formElement->setParentName( $this->getNameAsParent() );
				$output .= '<div class="form-group form-group-input">';
				$output .= $formElement->getHeaderElement();
				$output .= $formElement->getFormElement();
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
			$name = FormElement::sanitizeID( $name );
			if ( array_key_exists( $name, $this->_fieldMap ) )
			{
				return $this->_fieldMap[ $name ];
			}
			else
			{
				return NULL;
			}
		}

		public function getValueMapByElementName( $elementName )
		{
			$elementName = FormElement::sanitizeName( $elementName );
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
						$formElement = $this->getFormElementByElementName( $elementName );
						if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
						{
							return $formElement->getValueMapAt( $index );
						}
						else if ( is_a( $formElement, "\\tutomvc\\FormElement" ) )
						{
							return $formElement->getElementName();
						}
						else
						{
//							return $children;
						}
					}
					else
					{
						return $this->getValueMapAt( $index );
					}
				}
			}
			else if ( $elementName == $this->getElementName() )
			{
				return $this->getValueMapAt( NULL );
			}

			return FALSE;
		}

		public function getFormElementByElementName( $elementName )
		{
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				$formElement->setParentName( $this->getNameAsParent() );

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
					$formElement = $this->getFormElementByName( $key );
					if ( $formElement )
					{
						$formElement->setValue( $value );
					}
				}
			}
			else if ( is_null( $value ) )
			{
				/** @var FormElement $formElement */
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
					$formElement->setParentName( $this->getNameAsParent() );
					if ( strlen( $formElement->getElementName() ) ) $value[ $formElement->getElementName() ] = $formElement->getValue();
				}
			}

			return $value;
		}

		public function getValueMapAt( $index = NULL )
		{
			$oldIndex = $this->getIndex();
			$this->setIndex( $index );

			$valueMap = array();

			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				$formElement->setParentName( $this->getNameAsParent() );

				if ( strlen( $formElement->getName() ) )
				{
					if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
					{
						/** @var FormGroup $formElement */
						$valueMap[ $formElement->getName() ] = $formElement->getValueMapAt( $index );
					}
					else
					{
						$valueMap[ $formElement->getName() ] = $formElement->getElementName();
					}
				}
			}

			$this->setIndex( $oldIndex );

			return $valueMap;
		}

		public function getNameAsParent()
		{
			$name = $this->hasParent() ? "[" . $this->getName() . "]" : $this->getName();

			return $this->_parentName . $name;
		}
	}