<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 09:27
	 */

	namespace tutomvc\core\form;

	use tutomvc\core\model\NameObject;

	/**
	 * Base class of all classes in this package.
	 * @package tutomvc\core\form
	 */
	class FormElement extends NameObject
	{
		const REGEX_NAME          = "/([A-Za-z0-9-_]+)/ix";
		const REGEX_SANITIZE_ID   = "/[^A-Za-z0-9-]+/";
		const REGEX_SANITIZE_NAME = "/[^\[\]A-Za-z0-9-]+/";
		const REGEX_ELEMENT_NAME  = "/(.*?)\[([0-9]+)\](.*)/ix";
		const REGEX_GROUP_NAME    = "/\[([^\]]*)\]/ix";
		const CSS_CLASS           = "tutomvc-form-element";
		protected $_value;
		protected $_label;
		protected $_id;
		protected $_description;
		protected $_single = TRUE;
		protected $_index  = NULL;
		protected $_defaultValue;
		protected $_parentName;
		protected $_validationMethod;
		protected $_errorMessage;

		/**
		 * @param string $name A single name-identifier. Whatever string you like.
		 */
		function __construct( $name )
		{
			$this->_name = self::sanitizeID( $name );
		}

		/**
		 * Sanitizes a string to be appropriate for the id-attribute on HTML-elements.
		 *
		 * @param string $name
		 *
		 * @return mixed
		 */
		final public static function sanitizeID( $name )
		{
			return preg_replace( self::REGEX_SANITIZE_ID, '_', $name );
		}

		/**
		 * Sanitizes a string to be appropriate for the name-attribute on HTML-elements.
		 *
		 * @param string $elementName
		 *
		 * @return mixed
		 */
		final public static function sanitizeName( $elementName )
		{
			return preg_replace( self::REGEX_SANITIZE_NAME, "_", $elementName );
		}

		/**
		 * @param string $elementName
		 *
		 * @return mixed
		 */
		final public static function matchElementName( $elementName )
		{
			preg_match( self::REGEX_ELEMENT_NAME, $elementName, $matches );

			return $matches;
		}

		/**
		 * @param string $elementName
		 *
		 * @return null|string
		 */
		final public static function extractNames( $elementName )
		{
			preg_match_all( self::REGEX_NAME, $elementName, $matches );

			if ( count( $matches ) == 2 && !empty( $matches[ 1 ] ) ) return $matches[ 1 ];

			return NULL;
		}

		/**
		 * @param string $elementName
		 *
		 * @return null|string
		 */
		final public static function extractAncestorName( $elementName )
		{
			$matches = FormElement::matchElementName( $elementName );

			if ( count( $matches ) ) return $matches[ 1 ];

			return NULL;
		}

		/**
		 * @param string $elementName
		 *
		 * @return null|string
		 */
		final public static function extractAncestorIndex( $elementName )
		{
			$matches = FormElement::matchElementName( $elementName );

			if ( count( $matches ) >= 3 ) return $matches[ 2 ];

			return NULL;
		}

		/**
		 * @param string $elementName
		 *
		 * @return null|string
		 */
		final public static function extractGroupNames( $elementName )
		{
			preg_match_all( "/\[([^\]]*)\]/ix", $elementName, $matches );

			if ( count( $matches ) == 2 && !empty( $matches[ 1 ] ) ) return $matches[ 1 ];

			return NULL;
		}

		/**
		 * Parse the data as array ($_POST) and the function will automatically parse the data to the form.
		 * Same as setValue( $_POST[ name ] )
		 *
		 * @param array $dataArray
		 *
		 * @return bool TRUE if exists data, FALSE if no data exists.
		 */
		public function parse( $dataArray )
		{
			if ( isset( $dataArray[ $this->getName() ] ) )
			{
				$this->setValue( $dataArray[ $this->getName() ] );

				return TRUE;
			}

			return FALSE;
		}

		/**
		 * Removes error message for user.
		 */
		public function clearError()
		{
			$this->setErrorMessage( NULL );
		}

		/**
		 * Possibility to validate the value of the FormElement through call_user_func_array.
		 *
		 * @return string|null
		 * @see {@link setValidationMethod}
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

					return $filter;
				}
			}

			return NULL;
		}

		/* SET AND GET */
		/**
		 * Label / title.
		 *
		 * @param string $title
		 *
		 */
		public function setLabel( $title )
		{
			$this->_label = $title;

		}

		/**
		 * @return null|string
		 * @see {@link setLabel}
		 */
		public function getLabel()
		{
			return $this->_label;
		}

		/**
		 * A short help text about this form element.
		 *
		 * @param $description
		 *
		 */
		public function setDescription( $description )
		{
			$this->_description = $description;

		}

		/**
		 * @return null|string
		 * @see {@link setDescription}
		 */
		public function getDescription()
		{
			return $this->_description;
		}

		/**
		 * An array that contains all the attributes and attribute-data that the element will output.
		 * @return array
		 */
		function getFormElementAttributes()
		{
			return array();
		}

		/**
		 * @return string
		 * @see FormElement::getFormElementAttributes() Creates a string based on this array.
		 */
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
		 * Render and echo output HTML.
		 */
		public function output()
		{
			echo $this->formatOutput();
		}

		/**
		 * Generates the HTML for output. Including header-, error-, form- and footer- element.
		 * @return string
		 * @see {@link getFormElement}
		 * @see {@link getHeaderElement}
		 * @see {@link getErrorMessageElement}
		 * @see {@link getFooterElement}
		 */
		public function formatOutput()
		{
			$output = "";
			if ( $this->hasError() ) $output .= '<div class="form-group has-error ' . self::CSS_CLASS . '">';
			else $output .= '<div class="form-group ' . self::CSS_CLASS . '">';
			$output .= $this->formatHeaderOutput();
			$output .= $this->formatErrorMessageOutput();
			$output .= $this->formatFormElementOutput();
			$output .= $this->formatFooterOutput();
			$output .= '</div>';

			return $output;
		}

		/**
		 * Generates HTML for header-element. Normally just the label-element.
		 *
		 * @return string
		 */
		public function formatHeaderOutput()
		{
			return '<label class="control-label" for="' . $this->getID() . '">' . $this->getLabel() . '</label>';
		}

		/**
		 * Generates HTML for footer-element. Normally the description in a span-element.
		 *
		 * @return string
		 * @see {@link setDescription}
		 */
		public function formatFooterOutput()
		{
			$desc = $this->getDescription();

			return is_string( $desc ) && strlen( $desc ) ? '<span class="help-block">' . $this->getDescription() . '</span>' : '';
		}

		/**
		 * Generates div containing error message.
		 * @return string
		 * @see {@link setErrorMessage}
		 */
		public function formatErrorMessageOutput()
		{
			if ( is_string( $this->getErrorMessage() ) )
			{
				return '<div class="alert alert-danger" role="alert">' . $this->getErrorMessage() . '</div>';
			}

			return '';
		}

		/**
		 * Generates HTML for form-element. Normally a input-element with type-attr. Or textarea, select, etc.
		 *
		 * @return string
		 */
		public function formatFormElementOutput()
		{
			return '';
		}

		/**
		 * @return boolean
		 * @see {@link setSingle}
		 */
		public function isSingle()
		{
			return $this->_single;
		}

		/**
		 * Whether this form element just contains a single input of data.
		 * If not, the data/value usually is an array and the element name ends with [].
		 *
		 * @param boolean $single
		 *
		 */
		public function setSingle( $single )
		{
			$this->_single = $single;

		}

		/**
		 * @return null|int
		 * @see {@link setIndex}
		 */
		public function getIndex()
		{
			return $this->_index;
		}

		/**
		 * Will be used if this single is set to false.
		 *
		 * @param null|int $index
		 */
		public function setIndex( $index )
		{
			$this->_index = $index;
		}

		/**
		 * Should only be set from constructor and never be changed after that.
		 *
		 * @param string $name
		 *
		 * @throws \ErrorException
		 */
		public function setName( $name )
		{
			throw new \ErrorException( "Name can only be set from constructor.", 0, E_ERROR );
		}

		/**
		 * Returns a autogenerated ID that is a sanitized version of the element name.
		 * @return string
		 * @see {@link sanitizeID}
		 * @see {@link getElementName}
		 */
		public function getID()
		{
			return FormElement::sanitizeID( $this->getElementName() );
		}

		/**
		 * The value.
		 *
		 * @param $value
		 *
		 */
		public function setValue( $value )
		{
			$this->_value = $value;
		}

		/**
		 * @param callable|null $call_user_func Set a callable method that will filter the value before returned.
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
		 * @see {@link setDefaultValue}
		 */
		public function getDefaultValue()
		{
			return $this->_defaultValue;
		}

		/**
		 * If {@link _value} equals NULL, this value will be returned as default by {@link getValue}.
		 *
		 * @param mixed $defaultValue
		 *
		 */
		public function setDefaultValue( $defaultValue )
		{
			$this->_defaultValue = $defaultValue;
		}

		/**
		 * Is this a nested form element?
		 * @return boolean
		 * @see {@link setParentName}
		 */
		public function hasParent()
		{
			return !empty( $this->_parentName );
		}

		/**
		 * If this form element is nested, it will have a parent name.
		 *
		 * @param string $parentName
		 *
		 */
		public function setParentName( $parentName )
		{
			$this->_parentName = $parentName;
		}

		/**
		 * @return mixed
		 * @see {@link setParentName}
		 */
		public function getParentName()
		{
			return $this->_parentName;
		}

		/**
		 * The name-attr of the form element. It is autogenerated based on parent names, if it's a single-valued-form-element, and index-value.
		 * @return string
		 * @see {@link setParentName}
		 * @see {@link setSingle}
		 * @see {@link setIndex}
		 */
		public function getElementName()
		{
			$name = $this->getName();
			if ( !strlen( $name ) ) return NULL;
			if ( $this->hasParent() ) $name = $this->_parentName . "[" . $name . "]";

			return $this->isSingle() ? $name : $name . "[" . $this->getIndex() . "]";
		}

		/**
		 * @return callable|null
		 * @see {@link setValidationMethod}
		 */
		public function getValidationMethod()
		{
			return $this->_validationMethod;
		}

		/**
		 * Possibility to validate the value of the FormElement through call_user_func_array.<br/>
		 * The callable should return TRUE|NULL (if no errors are found) or a string with a error message on failure.<br/>
		 * The callable will receive two arguments in following order:<br/>
		 * 1. The value<br/>
		 * 2. The object instance
		 *
		 * @param callable $validationMethod
		 *
		 */
		public function setValidationMethod( $validationMethod )
		{
			$this->_validationMethod = $validationMethod;
		}

		/**
		 * @return mixed
		 * @see {@link setErrorMessage}
		 */
		public function getErrorMessage()
		{
			return $this->_errorMessage;
		}

		/**
		 * If a error message is set, then the form contains errors.<br/>
		 * The error message is normally automatically set when running {@link validate} which is calling the method is set via {@link setValidationMethod}
		 *
		 * @return bool
		 * @see {@link setErrorMessage}
		 * @see {@link setValidationMethod}
		 * @see {@link validate}
		 */
		public function hasError()
		{
			return !empty( $this->getErrorMessage() );
		}

		/**
		 * Error message for the user.
		 *
		 * @param string $errorMessage
		 *
		 */
		public function setErrorMessage( $errorMessage )
		{
			$this->_errorMessage = $errorMessage;
		}

		/**
		 * A single name-identifier. Whatever string you like sets through the constructor.<br/>
		 * **NOT** the same as elementName.
		 * @return string
		 * @see {@link __construct}
		 */
		public function getName()
		{
			return $this->_name;
		}
	}