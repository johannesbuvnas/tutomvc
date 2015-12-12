<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 09:27
	 */

	namespace tutomvc;

	/**
	 * Class FormElement
	 * The base element of a Form.
	 * @package tutomvc
	 */
	class FormElement extends NameObject
	{
		const REGEX_NAME          = "/([A-Za-z0-9-_]+)/ix";
		const REGEX_SANITIZE_ID   = "/[^A-Za-z0-9-]+/";
		const REGEX_SANITIZE_NAME = "/[^\[\]A-Za-z0-9-]+/";
		const REGEX_ELEMENT_NAME  = "/(.*)\[([0-9]+)\](.*)/ix";
		const REGEX_GROUP_NAME    = "/\[([^\]]*)\]/ix";
		protected $_name;
		protected $_value;
		protected $_label;
		protected $_id;
		protected $_description;
		protected $_rules  = array();
		protected $_single = TRUE;
		protected $_index  = NULL;
		protected $_defaultValue;
		protected $_parentName;
		protected $_sanitizedName;
		protected $_validationMethod;
		protected $_errorMessage;

		public function __construct( $name )
		{
			$this->_name = self::sanitizeID( $name );
		}

		final public static function sanitizeID( $name )
		{
			return preg_replace( self::REGEX_SANITIZE_ID, '_', $name );
		}

		final public static function sanitizeName( $elementName )
		{
			return preg_replace( self::REGEX_SANITIZE_NAME, "_", $elementName );
		}

		final public static function matchElementName( $elementName )
		{
			preg_match( self::REGEX_ELEMENT_NAME, $elementName, $matches );

			return $matches;
		}

		final public static function extractNames( $elementName )
		{
			preg_match_all( self::REGEX_NAME, $elementName, $matches );

			if ( count( $matches ) == 2 && !empty($matches[ 1 ]) ) return $matches[ 1 ];

			return NULL;
		}

		final public static function extractAncestorName( $elementName )
		{
			$matches = FormElement::matchElementName( $elementName );

			if ( count( $matches ) ) return $matches[ 1 ];

			return NULL;
		}

		final public static function extractAncestorIndex( $elementName )
		{
			$matches = FormElement::matchElementName( $elementName );

			if ( count( $matches ) >= 3 ) return $matches[ 2 ];

			return NULL;
		}

		final public static function extractGroupNames( $elementName )
		{
			preg_match_all( "/\[([^\]]*)\]/ix", $elementName, $matches );

			if ( count( $matches ) == 2 && !empty($matches[ 1 ]) ) return $matches[ 1 ];

			return NULL;
		}

		/**
		 * Parse the data as array ($_POST) and the function will automatically parse the data to the form.
		 * Same as setValue( $_POST[ name ] )
		 *
		 * @param array $dataArray
		 *
		 * @return bool TRUE if there exists data, FALSE if no data exists.
		 */
		public function parse( $dataArray )
		{
			if ( isset($dataArray[ $this->getName() ]) )
			{
				$this->setValue( $dataArray[ $this->getName() ] );

				return TRUE;
			}

			return FALSE;
		}

		public function clearError()
		{
			$this->setErrorMessage( NULL );
		}

		/**
		 * Possibility to validate the value of the FormElement through call_user_func_array.
		 * The callable function parsed should return TRUE or a string with a error message on failure.
		 * @return bool|mixed
		 * @internal param null $call_user_func
		 *
		 */
		public function validate()
		{
			if ( !is_null( $this->getValidationMethod() ) )
			{
				$filter = call_user_func_array( $this->getValidationMethod(), array(
					&$this,
					$this->getValue()
				) );
				if ( is_string( $filter ) )
				{
					$this->setErrorMessage( $filter );
				}
			}

			return $this;
		}

		public function addRule( Rule $rule )
		{
			$this->_rules[ ] = $rule;
		}

		/* SET AND GET */
		public function setLabel( $title )
		{
			$this->_label = $title;

			return $this;
		}

		public function getLabel()
		{
			return $this->_label;
		}

		public function setDescription( $description )
		{
			$this->_description = $description;

			return $this;
		}

		public function getDescription()
		{
			return $this->_description;
		}

		public function getRules()
		{
			return $this->_rules;
		}

		function getFormElementAttributes()
		{
			return array();
		}

		function getFormElementAttributesAsString()
		{
			$attributes = "";
			foreach ( (array)$this->getFormElementAttributes() as $key => $value )
			{
				$attributes .= ' ' . $key . '="' . $value . '"';
			}

			return $attributes;
		}

		/**
		 * Get the whole  <div /> including header-, error-, form- and footer- element.
		 * @return string
		 */
		public function getElement()
		{
			$output = "";
			if ( $this->hasError() ) $output .= '<div class="form-group has-error form-group-input">';
			else $output .= '<div class="form-group form-group-input">';
			$output .= $this->getHeaderElement();
			$output .= $this->getErrorMessageElement();
			$output .= $this->getFormElement();
			$output .= $this->getFooterElement();
			$output .= '</div>';

			return $output;
		}

		/**
		 * @return string The <header/> element.
		 */
		public function getHeaderElement()
		{
			return '<label class="control-label" for="' . $this->getID() . '">' . $this->getLabel() . '</label>';
		}

		/**
		 * @return string The <footer/> element.
		 */
		public function getFooterElement()
		{
			return '<span class="help-block">' . $this->getDescription() . '</span>';
		}

		/**
		 * @return string The <div class="alert"/> element.
		 */
		public function getErrorMessageElement()
		{
			if ( is_string( $this->getErrorMessage() ) )
			{
				return '<div class="alert alert-danger" role="alert">' . $this->getErrorMessage() . '</div>';
			}

			return '';
		}

		/**
		 * @return string The <input/> element.
		 */
		public function getFormElement()
		{
			return '';
		}

		/**
		 * @return boolean
		 */
		public function isSingle()
		{
			return $this->_single;
		}

		/**
		 * @param boolean $single
		 *
		 * @return $this
		 */
		public function setSingle( $single )
		{
			$this->_single = $single;

			return $this;
		}

		/**
		 * @return null|int
		 */
		public function getIndex()
		{
			return $this->_index;
		}

		/**
		 * Will be used if this single is set to false.
		 *
		 * @param null|int $index
		 *
		 * @return $this
		 */
		public function setIndex( $index )
		{
			$this->_index = $index;

			return $this;
		}

		public function setName( $name )
		{
			throw new \ErrorException( "Name can only be set from constructor.", 0, E_ERROR );
		}

		/**
		 * @return string
		 */
		public function getID()
		{
			return FormElement::sanitizeID( $this->getElementName() );
		}

		public function setValue( $value )
		{
			$this->_value = $value;

			return $this;
		}

		/**
		 * @param null $call_user_func
		 *
		 * @return mixed
		 */
		public function getValue( $call_user_func = NULL )
		{
			$value = is_null( $this->_value ) ? $this->getDefaultValue() : $this->_value;

			if ( !is_null( $call_user_func ) ) return call_user_func_array( $call_user_func, array(&$this, $value) );
			else return $value;
		}

		/**
		 * @return mixed
		 */
		public function getDefaultValue()
		{
			return $this->_defaultValue;
		}

		/**
		 * @param mixed $defaultValue
		 *
		 * @return $this
		 */
		public function setDefaultValue( $defaultValue )
		{
			$this->_defaultValue = $defaultValue;

			return $this;
		}

		/**
		 * @return boolean
		 */
		public function hasParent()
		{
			return !empty($this->_parentName);
		}

		/**
		 * @param string $parentName
		 *
		 * @return $this
		 */
		public function setParentName( $parentName )
		{
			$this->_parentName = $parentName;

			return $this;
		}

		public function getParentName()
		{
			return $this->_parentName;
		}

		/**
		 * The name-attribute.
		 * @return string
		 */
		public function getElementName()
		{
			$name = $this->getName();
			if ( !strlen( $name ) ) return NULL;
			if ( $this->hasParent() ) $name = $this->_parentName . "[" . $name . "]";

			return $this->isSingle() ? $name : $name . "[" . $this->getIndex() . "]";
		}

		/**
		 * @return mixed
		 */
		public function getValidationMethod()
		{
			return $this->_validationMethod;
		}

		/**
		 * Possibility to validate the value of the FormElement through call_user_func_array.
		 * The callable function should return TRUE or a string with a error message on failure.
		 *
		 * @param mixed $validationMethod
		 *
		 * @return $this
		 */
		public function setValidationMethod( $validationMethod )
		{
			$this->_validationMethod = $validationMethod;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getErrorMessage()
		{
			return $this->_errorMessage;
		}

		/**
		 * @return bool
		 */
		public function hasError()
		{
			return is_string( $this->getErrorMessage() );
		}

		/**
		 * @param mixed $errorMessage
		 *
		 * @return $this
		 */
		public function setErrorMessage( $errorMessage )
		{
			$this->_errorMessage = $errorMessage;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getName()
		{
			return $this->_name;
		}
	}