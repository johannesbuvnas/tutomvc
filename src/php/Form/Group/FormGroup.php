<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 09:13
	 */

	namespace TutoMVC\Form\Group;

	use TutoMVC\Form\Formatter\IFormElementFormatter;
	use TutoMVC\Form\FormElement;
	use TutoMVC\Form\Input\FormInput;

	/**
	 * A group of form elements. Can be other FormGroups or FormInputs.
	 *
	 * @see \TutoMVC\Form\Input\FormInput FormInput
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
		public function add( FormElement $formElement )
		{
			$this->_formElementsMap[ $formElement->getName() ] = $formElement;
			$formElement->setParentName( $this->getNameAsParent() );
			$formElement->setFormatter( $this->getFormatter() );

			return $formElement;
		}

		public function has( $name )
		{
			return array_key_exists( $name, $this->_formElementsMap );
		}

		/**
		 * Only removes the element if it's a direct child of this group.
		 *
		 * @param $name
		 *
		 * @return bool
		 *
		 */
		public function removeByName( $name )
		{
			$formElement = $this->findByName( $name, FALSE );

			if ( $formElement )
			{
				unset( $this->_formElementsMap[ $name ] );
				$formElement = NULL;

				return TRUE;
			}

			return FALSE;
		}

		/**
		 * Try to find a element by a certain name, first in this current group, and then in children. Returns the first match.
		 *
		 * @param $name
		 * @param bool $searchInChildGroups If not found in current group
		 *
		 * @return null|FormElement|FormGroup|FormInput|FissileFormGroup
		 * @see FormGroup::findByElementName() For more accurate search.
		 *
		 */
		public function findByName( $name, $searchInChildGroups = TRUE )
		{
			$name = self::sanitizeID( $name );

			if ( $this->has( $name ) )
			{
				return $this->_formElementsMap[ $name ];
			}

			if ( $searchInChildGroups )
			{
				foreach ( $this->getMap() as $formElement )
				{
					if ( $formElement instanceof FormGroup )
					{
						/** @var FormGroup $formElement */
						/** @var FormElement $subFormElement */
						$subFormElement = $formElement->findByName( $name );
						if ( $subFormElement ) return $subFormElement;
					}
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
		public function findByElementID( $elementID )
		{
			/** @var FormElement $formElement */
			foreach ( $this->getMap() as $formElement )
			{
				if ( $formElement->getID() == $elementID ) return $formElement;

				if ( $formElement instanceof FormGroup )
				{
					/** @var FormGroup $formElement */
					$search = $formElement->findByElementID( $elementID );
					if ( $search ) return $search;
				}
			}

			return NULL;
		}

		/**
		 * Search for the object based on the generated name-attr.
		 *
		 * @param $elementName
		 *
		 * @return null|FormElement|FormGroup|FormInput|FissileFormGroup
		 * @see {@link getElementName}
		 */
		public function findByElementName( $elementName )
		{
			$elementName = self::sanitizeName( $elementName );

			foreach ( $this->getMap() as $formElement )
			{
				if ( $formElement->getElementName() == $elementName ) return $formElement;

				if ( $formElement instanceof FormGroup )
				{
					$find = $formElement->findByElementName( $elementName );
					if ( $find ) return $find;
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

		public function validate()
		{
			parent::validate();

			/** @var FormElement $formElement */
			foreach ( $this->getMap() as $formElement )
			{
				$formElement->validate();
			}

			return $this->getErrors();
		}

		public function reset()
		{
			parent::reset();
			$this->clearErrors();
		}

		/**
		 * Remove errors.
		 */
		public function clearErrors()
		{
			$this->clearError();
			/** @var FormElement $formElement */
			foreach ( $this->getMap() as $formElement )
			{
				if ( $formElement instanceof FormGroup )
				{
					if ( !is_null( $formElement->getErrors() ) ) $formElement->clearErrors();
				}
				else
				{
					if ( is_string( $formElement->getErrorMessage() ) ) $formElement->clearError();
				}
			}
		}

		/* SET AND GET */
		/**
		 * Returns nested array of errors.
		 * If no errors exists, it returns NULL.
		 * @return array|null
		 */
		public function getErrors()
		{
			$errors = array();
			if ( is_string( $this->getErrorMessage() ) ) $errors[ $this->getElementName() ] = $this->getErrorMessage();
			foreach ( $this->getMap() as $formElement )
			{
				if ( $formElement instanceof FissileFormGroup )
				{
					$formElementErrors = $formElement->getFissionErrors();
					if ( !is_null( $formElementErrors ) || is_string( $formElement->getErrorMessage() ) )
					{
						if ( !is_array( $formElementErrors ) ) $formElementErrors = array();
						if ( is_string( $formElement->getErrorMessage() ) ) $formElementErrors[] = $formElement->getErrorMessage();
						$errors[ $formElement->getName() ] = $formElementErrors;
					}
				}
				else if ( $formElement instanceof FormGroup )
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
			foreach ( $this->getMap() as $formElement )
			{
				if ( $formElement instanceof FissileFormGroup )
				{
					$formGroupErrors = $formElement->getFlatFissionErrors();
					if ( is_array( $formGroupErrors ) ) $errors = array_merge( $errors, $formGroupErrors );
				}
				else if ( $formElement instanceof FormGroup )
				{
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
		 * @return FormElement[]
		 */
		public function getMap()
		{
			return $this->_formElementsMap;
		}

		/**
		 * @param FormElement[] $map
		 */
		public function setMap( $map )
		{
			$this->_formElementsMap = $map;
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
		public function getKeyMapByElementName( $elementName )
		{
			$elementName = FormElement::sanitizeName( $elementName );
			$matches     = FormElement::extractNames( $elementName );

			if ( count( $matches ) )
			{
				$i = 0;
				foreach ( $matches as $elementName )
				{
					$i ++;

					if ( $i == 1 && count( $matches ) == 1 && $elementName == $this->getElementName() ) return $this->getKeyMap();

					/** @var FormElement $formElement */
					$formElement = $this->findByName( $elementName, FALSE );
					if ( $formElement instanceof FormGroup )
					{
						if ( $i == count( $matches ) )
						{
							return $formElement->getKeyMap();
						}
						else
						{
							$namesLeft = array_slice( $matches, $i );

							return $formElement->getKeyMapByElementName( implode( "|", $namesLeft ) );
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
					$formElement = $this->findByName( $key, FALSE );
					if ( $formElement )
					{
						if ( $formElement instanceof FissileFormGroup )
						{
							$formElement->setFissions( $value );
						}
						else
						{
							$formElement->setValue( $value );
						}
					}
				}
			}
			else if ( is_null( $value ) )
			{
				/** @var FormElement $formElement */
				foreach ( $this->getMap() as $formElement )
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
			foreach ( $this->getMap() as $formElement )
			{
				if ( $formElement instanceof FissileFormGroup )
				{
					$value[ $formElement->getName() ] = $formElement->getFissions( $call_user_func );
				}
				else
				{
					$value[ $formElement->getName() ] = $formElement->getValue( $call_user_func );
				}
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
		 * @throws \ErrorException
		 */
		public function getFlatValue( $call_user_func = NULL )
		{
			$value = array();
			/** @var FormElement $formElement */
			foreach ( $this->getMap() as $formElement )
			{
				if ( $formElement instanceof FissileFormGroup )
				{
					$value = array_merge( $value, $formElement->getFlatFissions( $call_user_func ) );
				}
				else if ( $formElement instanceof FormGroup )
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
		 * Generates a array that contains where the key is the name of the element, and the value is the full element name.
		 *
		 * @return array
		 */
		public function getKeyMap()
		{
			$map = array();

			/** @var FormElement $formElement */
			foreach ( $this->getMap() as $formElement )
			{
				if ( strlen( $formElement->getName() ) )
				{
					if ( $formElement instanceof FissileFormGroup )
					{
						$map[ $formElement->getName() ] = $formElement->getFissionKeyMap();
					}
					else if ( $formElement instanceof FormGroup )
					{
						/** @var FormGroup $formElement */
						$map[ $formElement->getName() ] = $formElement->getKeyMap();
					}
					else
					{
						$map[ $formElement->getName() ] = $formElement->getElementName();
					}
				}
			}

			return $map;
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
			foreach ( $this->getMap() as $formElement )
			{
				$formElement->setParentName( $this->getNameAsParent() );
			}
		}

		public function setIndex( $index )
		{
			parent::setIndex( $index );

			/** @var FormElement $formElement */
			foreach ( $this->getMap() as $formElement )
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

		public function setFormatter( IFormElementFormatter $formatter )
		{
			parent::setFormatter( $formatter );

			foreach ( $this->getMap() as $formElement )
			{
				$formElement->setFormatter( $formatter );
			}
		}
	}
