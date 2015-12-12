<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 10:02
	 */

	namespace tutomvc;

	class FormInput extends FormElement
	{
		const TYPE_TEXT     = "text";
		const TYPE_PASSWORD = "password";
		const TYPE_HIDDEN   = "hidden";
		const TYPE_DATE     = "date";
		const TYPE_DATETIME = "datetime";
		const TYPE_NUMBER   = "number";
		const TYPE_FILE     = "file";

		protected $_type;
		protected $_readOnly;
		protected $_placeholder;
		protected $_accept;
		protected $_autocomplete = FALSE;
		protected $_autofocus    = FALSE;
		protected $_min;
		protected $_max;

		function __construct( $name, $title, $description = NULL, $type = FormInput::TYPE_TEXT, $readonly = FALSE, $placeholder = "", $single = TRUE )
		{
			parent::__construct( $name );
			$this->setLabel( $title );
			$this->setDescription( $description );
			$this->setType( $type );
			$this->setReadOnly( $readonly );
			$this->setPlaceholder( $placeholder );
			$this->setSingle( $single );
		}

		/**
		 * Get the whole  <div /> including header-, error-, form- and footer- element.
		 * @return string
		 */
		public function getElement()
		{
			$output     = "";
			$classNames = array(
				"form-group",
				"form-group-input"
			);
			if ( is_string( $this->getErrorMessage() ) ) $classNames[ ] = "has-error";
			if ( $this->getType() == self::TYPE_HIDDEN ) $classNames[ ] = "hidden";
			$output = '<div class="' . implode( " ", $classNames ) . '">';
			$output .= $this->getHeaderElement();
			$output .= $this->getErrorMessageElement();
			$output .= $this->getFormElement();
			$output .= $this->getFooterElement();
			$output .= '</div>';

			return $output;
		}

		/**
		 * The name-attribute.
		 * Will not create a nested-array-element-name if this is a file input type.
		 * @return string
		 */
		public function getElementName()
		{
			$name = parent::getElementName();

			if ( $this->getType() == self::TYPE_FILE ) $name = FormElement::sanitizeID( $name );

			return $name;
		}

		function getFormElement()
		{
			$output = "";


			$output .= '
					<input ' . $attributes . ' />
				';

			return $output;
		}

		function getFormElementAttributes()
		{
			$attr = array(
				"value"        => $this->getValue(),
				"type"         => $this->getType(),
				"placeholder"  => $this->getPlaceholder(),
				"name"         => $this->getElementName(),
				"id"           => $this->getID(),
				"class"        => "form-control form-input-element",
				"autocomplete" => $this->getAutocomplete() ? "on" : "off",
				"accept"       => $this->getAccept(),
			);
			if ( $this->isReadOnly() ) $attr[ "readonly" ] = "true";
			if ( $this->isAutofocus() ) $attr[ "autofocus" ] = "true";

			return $attr;
		}

		function getFormElementAttributesAsString()
		{
			$attributes = "";
			foreach ( $attr as $key => $value )
			{
				$attributes .= ' ' . $key . '="' . $value . '"';
			}
		}

		public function getHeaderElement()
		{
			return $this->getType() == self::TYPE_HIDDEN ? '' : '<label class="control-label" for="' . $this->getID() . '">' . $this->getLabel() . '</label>';
		}

		public function getFooterElement()
		{
			return $this->getType() == self::TYPE_HIDDEN ? '' : '<span class="help-block">' . $this->getDescription() . '</span>';
		}

		/**
		 * @param $value
		 *
		 * @return mixed
		 */
		public function filterValue( $value )
		{
			return $value;
		}

		/**
		 * @param string $value
		 *
		 * @return $this
		 */
		public function setValue( $value )
		{
			return parent::setValue( $value );
		}

		/**
		 * @return string
		 */
		public function getType()
		{
			return $this->_type;
		}

		/**
		 * @param string $type
		 *
		 * @return $this
		 */
		public function setType( $type )
		{
			$this->_type = $type;

			return $this;
		}

		/**
		 * @return boolean
		 */
		public function isReadOnly()
		{
			return $this->_readOnly;
		}

		/**
		 * @param boolean $readOnly
		 *
		 * @return $this
		 */
		public function setReadOnly( $readOnly )
		{
			$this->_readOnly = $readOnly;

			return $this;
		}

		/**
		 * @return string
		 */
		public function getPlaceholder()
		{
			return $this->_placeholder;
		}

		/**
		 * @param string $placeholder
		 *
		 * @return $this
		 */
		public function setPlaceholder( $placeholder )
		{
			$this->_placeholder = $placeholder;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getAccept()
		{
			return $this->_accept;
		}

		/**
		 * @param mixed $accept
		 *
		 * @return $this
		 */
		public function setAccept( $accept )
		{
			$this->_accept = $accept;

			return $this;
		}

		/**
		 * @return bool
		 */
		public function getAutocomplete()
		{
			return $this->_autocomplete;
		}

		/**
		 * @param bool $autocomplete
		 *
		 * @return $this
		 */
		public function setAutocomplete( $autocomplete )
		{
			$this->_autocomplete = $autocomplete;

			return $this;
		}

		/**
		 * Look for the element name in $_FILES IF this is a file-input-type.
		 * @return null|array
		 */
		public function getSubmittedFile()
		{
			if ( $this->getType() != self::TYPE_FILE ) return NULL;

			$file = array_key_exists( $this->getElementName(), $_FILES ) ? $_FILES[ $this->getElementName() ] : NULL;

			if ( is_array( $file ) && !empty($file[ 'size' ]) && empty($file[ 'error' ]) ) return $file;

			return NULL;
		}

		/**
		 * @return boolean
		 */
		public function isAutofocus()
		{
			return $this->_autofocus;
		}

		/**
		 * @param boolean $autofocus
		 *
		 * @return $this
		 */
		public function setAutofocus( $autofocus )
		{
			$this->_autofocus = $autofocus;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getMin()
		{
			return $this->_min;
		}

		/**
		 * @param mixed $min
		 */
		public function setMin( $min )
		{
			$this->_min = $min;
		}

		/**
		 * @return mixed
		 */
		public function getMax()
		{
			return $this->_max;
		}

		/**
		 * @param mixed $max
		 */
		public function setMax( $max )
		{
			$this->_max = $max;
		}
	}