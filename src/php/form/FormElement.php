<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 09:27
	 */

	namespace tutomvc;

	class FormElement extends ValueObject
	{
		const REGEX_SANITIZE_NAME = "/[^A-Za-z0-9-]+/";
		const REGEX_ELEMENT_NAME  = "/(.*)\[([0-9]+)\](.*)/ix";
		const REGEX_GROUP_NAME    = "/\[([^\]]*)\]/ix";
		protected $_label;
		protected $_id;
		protected $_description;
		protected $_rules  = array();
		protected $_single = TRUE;
		protected $_index  = NULL;
		protected $_defaultValue;
		protected $_parentName;
		protected $_sanitizedName;

		public function __construct( $name )
		{
			parent::setName( self::sanitizeName( $name ) );

			if ( $name != $this->getName() )
			{
				throw new \UnexpectedValueException( "Name was sanitized since it contained illegal characters." );
			}
		}

		final public static function sanitizeName( $name )
		{
			return preg_replace( self::REGEX_SANITIZE_NAME, '_', $name );
		}

		final public static function matchElementName( $elementName )
		{
			preg_match( self::REGEX_ELEMENT_NAME, $elementName, $matches );

			return $matches;
		}

		final public static function extractGroupNames( $elementName )
		{
			preg_match_all( "/\[([^\]]*)\]/ix", $elementName, $matches );

			if ( count( $matches ) == 2 && !empty($matches[ 1 ]) ) return $matches[ 1 ];

			return NULL;
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

		public function getHeaderElement()
		{
			return '<label for="' . $this->getID() . '">' . $this->getLabel() . '</label>';
		}

		public function getFooterElement()
		{
			return '<span class="help-block">' . $this->getDescription() . '</span>';
		}

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
			return $this->_id;
		}

		/**
		 * @param string $id
		 */
		public function setID( $id )
		{
			$this->_id = $id;

			return $this;
		}

		public function getValueMap()
		{
			return $this->getElementName();
		}

		public function getValue()
		{
			$value = parent::getValue();

			return empty($value) ? $this->getDefaultValue() : $value;
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
		 */
		public function setParentName( $parentName )
		{
			$this->_parentName = $parentName;

			return $this;
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
	}