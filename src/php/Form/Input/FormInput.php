<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 10:02
	 */

	namespace TutoMVC\Form\Input;

	use TutoMVC\Form\FormElement;
	use function htmlspecialchars;

	/**
	 * HTML element "input"
	 *
	 */
	class FormInput extends FormElement
	{
		const TYPE_TEXT           = "text";
		const TYPE_PASSWORD       = "password";
		const TYPE_HIDDEN         = "hidden";
		const TYPE_DATE           = "date";
		const TYPE_DATETIME       = "datetime";
		const TYPE_NUMBER         = "number";
		const TYPE_FILE           = "file";
		const TYPE_BUTTON         = "button";
		const TYPE_CHECKBOX       = "checkbox";
		const TYPE_COLOR          = "color";
		const TYPE_DATETIME_LOCAL = "datetime-local";
		const TYPE_EMAIL          = "email";
		const TYPE_IMAGE          = "image";
		const TYPE_MONTH          = "month";
		const TYPE_RADIO          = "radio";
		const TYPE_RANGE          = "range";
		const TYPE_RESET          = "reset";
		const TYPE_SEARCH         = "search";
		const TYPE_SUBMIT         = "submit";
		const TYPE_TEL            = "tel";
		const TYPE_TIME           = "time";
		const TYPE_URL            = "url";
		const TYPE_WEEK           = "week";

		protected $_autocomplete = FALSE;
		protected $_autofocus    = FALSE;
		protected $_required     = FALSE;
		protected $_checked      = FALSE;
		protected $_disabled     = FALSE;
		protected $_readOnly     = FALSE;
		protected $_type;
		protected $_placeholder;
		protected $_accept;
		protected $_min;
		protected $_max;
		protected $_maxlength;
		protected $_pattern;
		protected $_alt;
		protected $_size;
		protected $_src;
		protected $_step;
		protected $_width;
		protected $_height;

		function __construct( $name, $title = NULL, $description = NULL, $type = FormInput::TYPE_TEXT, $readonly = FALSE, $placeholder = "", $single = TRUE )
		{
			parent::__construct( $name );
			$this->setLabel( is_null( $title ) ? $name : $title );
			$this->setDescription( $description );
			$this->setType( $type );
			$this->setReadOnly( $readonly );
			$this->setPlaceholder( $placeholder );
			$this->setSingle( $single );
		}

		public function getElementName()
		{
			$name = parent::getElementName();

			if ( $this->getType() == self::TYPE_FILE ) $name = FormElement::sanitizeID( $name );

			return $name;
		}

		function getFormElementAttributes()
		{
			$attr = array(
				"type"         => $this->getType(),
				"name"         => $this->getElementName(),
				"id"           => $this->getID(),
				"class"        => "form-control form-input-element",
				"autocomplete" => $this->isAutocomplete() ? "on" : "off",
			);
			if ( $this->isReadOnly() ) $attr[ "readonly" ] = "readonly";
			if ( $this->isAutofocus() ) $attr[ "autofocus" ] = "autofocus";
			if ( $this->isRequired() ) $attr[ "required" ] = "required";
			if ( $this->isDisabled() ) $attr[ "disabled" ] = "disabled";
			if ( $this->isChecked() ) $attr[ "checked" ] = "checked";
			if ( $this->getMin() ) $attr[ "min" ] = $this->getMin();
			if ( $this->getMax() ) $attr[ "max" ] = $this->getMax();
			if ( $this->getMaxlength() ) $attr[ "maxlength" ] = $this->getMaxlength();
			if ( $this->getPattern() ) $attr[ "pattern" ] = $this->getPattern();
			if ( $this->getAlt() ) $attr[ "alt" ] = $this->getAlt();
			if ( $this->getSize() ) $attr[ "size" ] = $this->getSize();
			if ( $this->getSrc() ) $attr[ "src" ] = $this->getSrc();
			if ( $this->getStep() ) $attr[ "step" ] = $this->getStep();
			if ( $this->getWidth() ) $attr[ "width" ] = $this->getWidth();
			if ( $this->getHeight() ) $attr[ "height" ] = $this->getHeight();
			if ( !is_array( $this->getValue() ) && !is_null($this->getValue()) ) $attr[ "value" ] = $this->getValue();
			if ( $this->getPlaceholder() ) $attr[ "placeholder" ] = $this->getPlaceholder();
			if ( $this->getAccept() ) $attr[ "accept" ] = $this->getAccept();
			if ( $this->isMultiple() ) $attr[ "multiple" ] = "multiple";

			return $attr;
		}

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
		 * The type-attr of the element.
		 *
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
		 * Will add the readonly-attr.
		 *
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
		 * Will add the placeholder-attr.
		 *
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
		 * Specifies the types of files acceptable. Only if type is set to "file".
		 *
		 * @param string $accept file_extension | audio/* video/* image/* | media_type
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
		public function isAutocomplete()
		{
			return $this->_autocomplete;
		}

		/**
		 * Will add the autocomplete-attr.
		 *
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
		 *
		 * @return null|array
		 */
		public function getSubmittedFile()
		{
			if ( $this->getType() != self::TYPE_FILE ) return NULL;

			$file = array_key_exists( $this->getElementName(), $_FILES ) ? $_FILES[ $this->getElementName() ] : NULL;

			if ( is_array( $file ) && !empty( $file[ 'size' ] ) && empty( $file[ 'error' ] ) ) return $file;

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
		 * Will add the autofocus-attr.
		 *
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
		 * Will add the min-attr for date- and number-type.
		 *
		 * @param number|date $min
		 *
		 * @return $this
		 */
		public function setMin( $min )
		{
			$this->_min = $min;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getMax()
		{
			return $this->_max;
		}

		/**
		 * Will add the max-attr for date- and number-type.
		 *
		 * @param number|date $max
		 *
		 * @return $this
		 */
		public function setMax( $max )
		{
			$this->_max = $max;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getMaxlength()
		{
			return $this->_maxlength;
		}

		/**
		 * Will add the maxlength-attr.
		 *
		 * @param number $maxlength
		 *
		 * @return $this
		 */
		public function setMaxlength( $maxlength )
		{
			$this->_maxlength = $maxlength;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function isMultiple()
		{
			return !$this->isSingle();
		}

		/**
		 * Will add the multiple-attr and manipulate {@link setSingle}
		 *
		 * @param bool $multiple
		 *
		 * @return $this
		 */
		public function setMultiple( $multiple )
		{
			$this->setSingle( !$multiple );

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getPattern()
		{
			return $this->_pattern;
		}

		/**
		 * Will add the pattern-attr.
		 *
		 * @param regexp $pattern
		 *
		 * @return $this
		 */
		public function setPattern( $pattern )
		{
			$this->_pattern = $pattern;

			return $this;
		}

		/**
		 * @return boolean
		 */
		public function isRequired()
		{
			return $this->_required;
		}

		/**
		 * Will add the required-attr.
		 *
		 * @param boolean $required
		 *
		 * @return $this
		 */
		public function setRequired( $required )
		{
			$this->_required = $required;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getAlt()
		{
			return $this->_alt;
		}

		/**
		 * Specifies an alternate text for images (only for type="image")
		 *
		 * @param string $alt
		 *
		 * @return $this
		 */
		public function setAlt( $alt )
		{
			$this->_alt = $alt;

			return $this;
		}

		/**
		 * For checkboxes.
		 *
		 * @return boolean
		 */
		public function isChecked()
		{
			$value = $this->getValue();

			return !empty( $value );
		}

		/**
		 * @return boolean
		 */
		public function isDisabled()
		{
			return $this->_disabled;
		}

		/**
		 * Will add the disabled-attr.
		 *
		 * @param boolean $disabled
		 *
		 * @return $this
		 */
		public function setDisabled( $disabled )
		{
			$this->_disabled = $disabled;

			return $this;
		}

		/**
		 * @return number
		 */
		public function getSize()
		{
			return $this->_size;
		}

		/**
		 * Specifies the width, in characters.
		 *
		 * @param number $size
		 *
		 * @return $this
		 */
		public function setSize( $size )
		{
			$this->_size = $size;

			return $this;
		}

		/**
		 * @return string|null
		 */
		public function getSrc()
		{
			return $this->_src;
		}

		/**
		 * Specifies the URL of the image to use as a submit button (only for type="image")
		 * Will add the src-attr.
		 *
		 * @param string $src
		 *
		 * @return $this
		 */
		public function setSrc( $src )
		{
			$this->_src = $src;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getStep()
		{
			return $this->_step;
		}

		/**
		 * Specifies the legal number intervals for an input field.
		 * Will add the step-attr.
		 *
		 * @param mixed $step
		 *
		 * @return $this
		 */
		public function setStep( $step )
		{
			$this->_step = $step;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getWidth()
		{
			return $this->_width;
		}

		/**
		 * Specifies the width of an input-element (only for type="image")
		 * Will add the width-attr.
		 *
		 * @param mixed $width Pixels
		 *
		 * @return $this
		 */
		public function setWidth( $width )
		{
			$this->_width = $width;

			return $this;
		}

		/**
		 * @return mixed
		 */
		public function getHeight()
		{
			return $this->_height;
		}

		/**
		 * Specifies the height of an input-element (only for type="image")
		 * Will add the height-attr.
		 *
		 * @param mixed $height Pixels
		 *
		 * @return $this
		 */
		public function setHeight( $height )
		{
			$this->_height = $height;

			return $this;
		}
	}
