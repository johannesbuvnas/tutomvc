<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 09:13
	 */

	namespace tutomvc\core\form\groups;

	use tutomvc\core\form\FormElement;

	/**
	 * A group of form elements. Can be other FormGroups or FormInputs.
	 *
	 * @see \tutomvc\core\form\inputs\FormInput FormInput
	 * @package tutomvc\core\form\groups
	 */
	class FormGroup extends FormElement
	{
		protected $_formElementsMap = array();

		function __construct( $name, $title = NULL, $description = NULL )
		{
			parent::__construct( $name );
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
		 * Only removes the element if it's a direct child of this group.
		 *
		 * @param $formElementName
		 *
		 * @return bool
		 */
		public function removeFormElement( $formElementName )
		{
			if ( $formElement = $this->getFormElementByName( $formElementName ) )
			{
				unset( $this->_formElementsMap[ $formElementName ] );
				$formElement = NULL;

				return TRUE;
			}

			return FALSE;
		}

		/**
		 * Try to find a element by a certain name, first in this current group, and then in children. Returns the first match.
		 *
		 * @param $name
		 * @see FormGroup::findFormElementByElementName() For more accurate search.
		 *
		 * @return null|FormElement|FormGroup
		 */
		public function findFormElementByName( $name )
		{
			$name        = self::sanitizeID( $name );
			$formElement = $this->getFormElementByName( $name );

			/** @var FormElement $formElement */
			if ( $formElement ) return $formElement;

			foreach ( $this->getFormElements() as $formElement )
			{
				if ( $formElement instanceof FormGroup )
				{
					/** @var FormGroup $formElement */
					/** @var FormElement $subFormElement */
					$subFormElement = $formElement->findFormElementByName( $name );
					if ( $subFormElement ) return $subFormElement;
				}
			}

			return NULL;
		}

		/**
		 * Search for the object based on the generated name-attr.
		 *
		 * @param $elementName
		 *
		 * @return null|FormElement|FormGroup
		 * @see {@link getElementName}
		 */
		public function findFormElementByElementName( $elementName )
		{
			$elementName = self::sanitizeName( $elementName );
			$formElement = $this->getFormElementByElementName( $elementName );

			/** @var FormElement $formElement */
			if ( $formElement ) return $formElement;

			foreach ( $this->getFormElements() as $formElement )
			{
				if ( $formElement instanceof FormGroup )
				{
					/** @var FormGroup $formElement */
					/** @var FormElement $subFormElement */
					$subFormElement = $formElement->findFormElementByElementName( $elementName );
					if ( $subFormElement ) return $subFormElement;
				}
			}

			return NULL;
		}

		public function formatRootElementName( $rootName )
		{
			$name     = $this->hasParent() ? "[" . $this->getName() . "]" : $this->getName();
			$rootName = strval( $rootName );

			return is_string( $rootName ) && strlen( $rootName ) ? $this->_parentName . $name . "[" . $rootName . "]" : $this->_parentName . $name;
		}

		/* SET AND GET */
		public function formatHeaderOutput()
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

		public function formatFooterOutput()
		{
			return '<hr/>';
		}

		function formatFormElementOutput()
		{
			$output = '<div class="form-group" id="' . $this->getID() . '">';
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				$output .= $formElement->formatOutput();
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

			return $this->getErrors();
		}

		/**
		 * Remove errors.
		 */
		public function clearErrors()
		{
			$this->clearError();
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( $formElement instanceof FormGroup )
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
		 * Returns nested array of errors.
		 * If no errors exists, it returns NULL.
		 * @return array|null
		 */
		public function getErrors()
		{
			$errors = array();
			if ( is_string( $this->getErrorMessage() ) ) $errors[ $this->getElementName() ] = $this->getErrorMessage();
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( $formElement instanceof FormGroup )
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
		 * Returns flat array of errors.
		 * If no errors exists, it returns NULL.
		 * @return array|null
		 */
		public function getFlatErrors()
		{
			$errors = array();

			if ( is_string( $this->getErrorMessage() ) ) $errors[ $this->getElementName() ] = $this->getErrorMessage();

			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( $formElement instanceof FormGroup )
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
		 * @param array <FormElement> $map
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

		/**
		 * Try to find a child by it's element name.<br/>
		 * If the child is a FormGroup: it will return a nested array with key equals the given name, and the value equals the autogenerated name-attr / element name.<br/>
		 * If the child is not a FormGroup: it will return the element name.<br/>
		 * If the child is not found: it will return FALSE.
		 *
		 * @param string $elementName
		 *
		 * @return array|bool|string
		 */
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
					if ( $formElement instanceof FormGroup )
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
					else if ( $formElement instanceof FormElement )
					{
						if ( $i == count( $matches ) ) return $formElement->getElementName();
					}
				}
			}

			return FALSE;
		}

		/**
		 * Search for a child by it's autogenerated name-attr / element name.<br/>
		 * Searches through child FormGroups as well.
		 *
		 * @param $elementName
		 *
		 * @return null|FormElement|FormGroup
		 */
		public function getFormElementByElementName( $elementName )
		{
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( $formElement->getElementName() == $elementName ) return $formElement;

				if ( $formElement instanceof FormGroup )
				{
					/** @var FormGroup $formElement */
					$search = $formElement->getFormElementByElementName( $elementName );
					if ( $search ) return $search;
				}
			}

			return NULL;
		}

		/**
		 * Search for a child by it's id-attr.<br/>
		 * Searches through child FormGroups as well.
		 *
		 * @param $elementID
		 *
		 * @return null|FormElement|FormGroup
		 */
		public function getFormElementByElementID( $elementID )
		{
			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				if ( $formElement->getID() == $elementID ) return $formElement;

				if ( $formElement instanceof FormGroup )
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
		}

		/**
		 * Generates a nested value-array.
		 *
		 * @param callable|null $call_user_func Set a callable method that will filter the value before returned.
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
		 * Generates a non-nested array with the key equals the name-attr / element name.
		 *
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
				if ( $formElement instanceof FormGroup )
				{
					/** @var FormGroup $formElement */
					$value = array_merge( $value, $formElement->getFlatValue( $call_user_func ) );
				}
				else
				{
					if ( strlen( $formElement->getElementName() ) ) $value[ $formElement->getElementName() ] = $formElement->getValue( $call_user_func );
				}
			}

			return $value;
		}

		/**
		 * Generates a value map with a specific index.
		 *
		 * @param null $index
		 *
		 * @return array
		 */
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
					if ( $formElement instanceof FormGroup )
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

		/**
		 * @return string
		 */
		public function getNameAsParent()
		{
			return $this->formatRootElementName( $this->getIndex() );
		}

		public function setParentName( $parentName )
		{
			parent::setParentName( $parentName );

			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				$formElement->setParentName( $this->getNameAsParent() );
			}
		}

		public function setIndex( $index )
		{
			parent::setIndex( $index );

			/** @var FormElement $formElement */
			foreach ( $this->getFormElements() as $formElement )
			{
				$formElement->setParentName( $this->getNameAsParent() );
			}
		}

		/**
		 * If a error message is set in this element or any children, then the form contains errors.<br/>
		 * The error message is normally automatically set when running {@link validate} which is calling the method is set via {@link setValidationMethod}
		 *
		 * @return bool
		 * @see {@link setErrorMessage}
		 * @see {@link setValidationMethod}
		 * @see {@link validate}
		 */
		public function hasError()
		{
			return !empty( $this->getErrors() );
		}
	}