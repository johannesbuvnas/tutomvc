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
		protected $_formElementsMap = array();

		function __construct( $name, $title = NULL, $description = NULL )
		{
			parent::__construct( $name, NULL );
			$this->setLabel( $title );
			$this->setDescription( $description );
		}

		/**
		 * @param FormElement $formElement
		 *
		 * @return FormElement
		 */
		public function addFormElement( FormElement $formElement )
		{
			$this->_formElementsMap[ $formElement->getName() ] = $formElement;
			$formElement->setParentName( $this->getNameAsParent() );

			return $formElement;
		}

		/**
		 * Try to find a FormElement by a certain name, first in this current FormGroup, and then in children. Returns the first match.
		 *
		 * @param $name
		 *
		 * @return null|FormElement|FormGroup
		 */
		public function findFormElementByName( $name )
		{
			$name        = self::sanitizeID( $name );
			$formElement = $this->getFormElementByName( $name );

			/** @var \tutomvc\FormElement $formElement */
			if ( $formElement ) return $formElement;

			foreach ( $this->getFormElements() as $formElement )
			{
				if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
				{
					/** @var \tutomvc\FormGroup $formElement */
					/** @var \tutomvc\FormElement $subFormElement */
					$subFormElement = $formElement->findFormElementByName( $name );
					if ( $subFormElement ) return $subFormElement;
				}
			}

			return NULL;
		}

		public function findFormElementByElementName( $elementName )
		{
			$elementName = self::sanitizeName( $elementName );
			$formElement = $this->getFormElementByElementName( $elementName );

			/** @var \tutomvc\FormElement $formElement */
			if ( $formElement ) return $formElement;

			foreach ( $this->getFormElements() as $formElement )
			{
				if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
				{
					/** @var \tutomvc\FormGroup $formElement */
					/** @var \tutomvc\FormElement $subFormElement */
					$subFormElement = $formElement->findFormElementByElementName( $elementName );
					if ( $subFormElement ) return $subFormElement;
				}
			}

			return NULL;
		}

		/* SET AND GET */
		public function getHeaderElement()
		{
			return '
					<header>
						<h2>
							' . $this->getLabel() . '
							<small class="help-block">
								' . $this->getDescription() . '
							</small>
						</h2>
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
				$output .= $formElement->getElement();
			}
			$output .= '</div>';

			return $output;
		}

		public function validate()
		{
			parent::validate();

			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				$formElement->validate();
			}

			return $this;
		}

		public function clearErrors()
		{
			$this->clearError();
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
				{
					/** @var FormGroup $formElement */
					if ( !is_null( $formElement->getErrors() ) ) $formElement->clearErrors();
				}
				else
				{
					if ( is_string( $formElement->getErrorMessage() ) ) $formElement->clearError();
				}
			}
		}

		/**
		 * Returns array of errors if errors exists.
		 * If no errors exists, it returns NULL.
		 * @return array|null
		 */
		public function getErrors()
		{
			$this->validate();
			$errors = array();
			if ( is_string( $this->getErrorMessage() ) ) $errors[ $this->getElementName() ] = $this->getErrorMessage();
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
				{
					/** @var FormGroup $formElement */
					if ( !is_null( $formElement->getErrors() ) || is_string( $formElement->getErrorMessage() ) )
					{
						$formElementErrors = $formElement->getErrors();
						if ( !is_array( $formElementErrors ) ) $formElementErrors = array();
						if ( is_string( $formElement->getErrorMessage() ) ) $formElementErrors[] = $formElement->getErrorMessage();
						$errors[ $formElement->getName() ] = $formElementErrors;
					}
				}
				else
				{
					if ( is_string( $formElement->getErrorMessage() ) ) $errors[ $formElement->getName() ] = $formElement->getErrorMessage();
				}
			}

			if ( count( $errors ) ) return $errors;
			else return NULL;
		}

		/**
		 * Returns array of errors if errors exists.
		 * If no errors exists, it returns NULL.
		 * @return array|null
		 */
		public function getFlatErrors()
		{
			$this->validate();
			$errors = array();

			if ( is_string( $this->getErrorMessage() ) ) $errors[ $this->getElementName() ] = $this->getErrorMessage();

			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
				{
					/** @var FormGroup $formElement */
					$formGroupErrors = $formElement->getFlatErrors();
					if ( is_array( $formGroupErrors ) ) $errors = array_merge( $errors, $formGroupErrors );
				}
				else
				{
					if ( is_string( $formElement->getErrorMessage() ) ) $errors[ $formElement->getElementName() ] = $formElement->getErrorMessage();
				}
			}

			if ( count( $errors ) ) return $errors;
			else return NULL;
		}

		/**
		 * @return array
		 */
		public function getFormElements()
		{
			return $this->_formElementsMap;
		}

		/**
		 * @param array $map
		 */
		public function setFormElements( $map )
		{
			$this->_formElementsMap = $map;
		}

		/**
		 * @param $name
		 *
		 * @return null|FormElement
		 */
		public function getFormElementByName( $name )
		{
			$name = FormElement::sanitizeID( $name );
			if ( array_key_exists( $name, $this->_formElementsMap ) )
			{
				return $this->_formElementsMap[ $name ];
			}
			else
			{
				return NULL;
			}
		}

		public function getValueMapByElementName( $elementName )
		{
			$elementName = FormElement::sanitizeName( $elementName );
			$matches     = FormElement::extractNames( $elementName );

			if ( count( $matches ) )
			{
				$i = 0;
				foreach ( $matches as $elementName )
				{
					$i ++;

					if ( $i == 1 && count( $matches ) == 1 && $elementName == $this->getElementName() ) return $this->getValueMapAt( NULL );

					/** @var FormElement $formElement */
					$formElement = $this->getFormElementByName( $elementName );
					if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
					{
						/** @var FormGroup $formElement */
						if ( $i == count( $matches ) )
						{
							return $formElement->getValueMapAt();
						}
						else
						{
							$namesLeft = array_slice( $matches, $i );

							return $formElement->getValueMapByElementName( implode( "|", $namesLeft ) );
						}
					}
					else if ( is_a( $formElement, "\\tutomvc\\FormElement" ) )
					{
						if ( $i == count( $matches ) ) return $formElement->getElementName();
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

		public function getFormElementByElementID( $elementID )
		{
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( $formElement->getID() == $elementID ) return $formElement;

				if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
				{
					/** @var FormGroup $formElement */
					$search = $formElement->getFormElementByElementID( $elementID );
					if ( $search ) return $search;
				}
			}

			return NULL;
		}

		/**
		 * @param array|null $value
		 *
		 * @return $this
		 * @throws \ErrorException
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
		 * @param callable|null $call_user_func
		 *
		 * @return array|mixed
		 */
		public function getValue( $call_user_func = NULL )
		{
			$value = array();
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				$value[ $formElement->getName() ] = $formElement->getValue( $call_user_func );
			}

			if ( !is_null( $call_user_func ) ) return call_user_func_array( $call_user_func, array(&$this, $value) );
			else return $value;
		}

		/**
		 * @param callable|null $call_user_func
		 *
		 * @return array|null
		 */
		public function getFlatValue( $call_user_func = NULL )
		{
			$value = array();
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
				{
					/** @var FormGroup $formElement */
					$value = array_merge( $value, $formElement->getFlatValue( $call_user_func ) );
				}
				else
				{
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

		/**
		 * @param string $parentName
		 *
		 * @return $this
		 */
		public function setParentName( $parentName )
		{
			parent::setParentName( $parentName );

			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				$formElement->setParentName( $this->getNameAsParent() );
			}

			return $this;
		}

		/**
		 * @return bool
		 */
		public function hasError()
		{
			return !empty($this->getErrors());
		}
	}