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
		const TYPE_DATETIME_LOCAL = "datetime-local ";
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
			if ( is_string( $this->getErrorMessage() ) ) $classNames[] = "has-error";
			if ( $this->getType() == self::TYPE_HIDDEN ) $classNames[] = "hidden";
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
					<input ' . $this->getFormElementAttributesAsString() . ' />
				';

			return $output;
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
			if ( strlen( $this->getMin() ) ) $attr[ "min" ] = $this->getMin();
			if ( strlen( $this->getMax() ) ) $attr[ "max" ] = $this->getMax();
			if ( strlen( $this->getMaxlength() ) ) $attr[ "maxlength" ] = $this->getMaxlength();
			if ( strlen( $this->getPattern() ) ) $attr[ "pattern" ] = $this->getPattern();
			if ( strlen( $this->getAlt() ) ) $attr[ "alt" ] = $this->getAlt();
			if ( strlen( $this->getSize() ) ) $attr[ "size" ] = $this->getSize();
			if ( strlen( $this->getSrc() ) ) $attr[ "src" ] = $this->getSrc();
			if ( strlen( $this->getStep() ) ) $attr[ "step" ] = $this->getStep();
			if ( strlen( $this->getWidth() ) ) $attr[ "width" ] = $this->getWidth();
			if ( strlen( $this->getHeight() ) ) $attr[ "height" ] = $this->getHeight();
			if ( !is_array( $this->getValue() ) && strlen( $this->getValue() ) ) $attr[ "value" ] = $this->getValue();
			if ( strlen( $this->getPlaceholder() ) ) $attr[ "placeholder" ] = $this->getPlaceholder();
			if ( strlen( $this->getAccept() ) ) $attr[ "accept" ] = $this->getAccept();
			if ( strlen( $this->isMultiple() ) ) $attr[ "multiple" ] = "multiple";

			return $attr;
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
		 * Specifies the types of files that the server accepts (only for type="file")
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
		 * Specifies whether an <input> element should have autocomplete enabled
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
		 * Specifies that an <input> element should automatically get focus when the page loads
		 *
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
		 * Specifies a minimum value for an <input> element
		 *
		 * @param number|date $min
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
		 * Specifies the maximum value for an <input> element
		 *
		 * @param number|date $max
		 */
		public function setMax( $max )
		{
			$this->_max = $max;
		}

		/**
		 * @return mixed
		 */
		public function getMaxlength()
		{
			return $this->_maxlength;
		}

		/**
		 *    Specifies the maximum number of characters allowed in an <input> element
		 *
		 * @param number $maxlength
		 */
		public function setMaxlength( $maxlength )
		{
			$this->_maxlength = $maxlength;
		}

		/**
		 * @return mixed
		 */
		public function isMultiple()
		{
			return !$this->isSingle();
		}

		/**
		 * Specifies that a user can enter more than one value in an <input> element
		 *
		 * @param bool $multiple
		 */
		public function setMultiple( $multiple )
		{
			$this->setSingle( !$multiple );
		}

		/**
		 * @return mixed
		 */
		public function getPattern()
		{
			return $this->_pattern;
		}

		/**
		 *    Specifies a regular expression that an <input> element's value is checked against
		 *
		 * @param regexp $pattern
		 */
		public function setPattern( $pattern )
		{
			$this->_pattern = $pattern;
		}

		/**
		 * @return boolean
		 */
		public function isRequired()
		{
			return $this->_required;
		}

		/**
		 *    Specifies that an input field must be filled out before submitting the form
		 *
		 * @param boolean $required
		 */
		public function setRequired( $required )
		{
			$this->_required = $required;
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
		 */
		public function setAlt( $alt )
		{
			$this->_alt = $alt;
		}

		/**
		 * @return boolean
		 */
		public function isChecked()
		{
			return $this->_checked;
		}

		/**
		 * Specifies that an <input> element should be pre-selected when the page loads (for type="checkbox" or type="radio")
		 *
		 * @param boolean $checked
		 */
		public function setChecked( $checked )
		{
			$this->_checked = $checked;
		}

		/**
		 * @return boolean
		 */
		public function isDisabled()
		{
			return $this->_disabled;
		}

		/**
		 *    Specifies that an <input> element should be disabled
		 *
		 * @param boolean $disabled
		 */
		public function setDisabled( $disabled )
		{
			$this->_disabled = $disabled;
		}

		/**
		 * @return number
		 */
		public function getSize()
		{
			return $this->_size;
		}

		/**
		 *    Specifies the width, in characters, of an <input> element
		 *
		 * @param number $size
		 */
		public function setSize( $size )
		{
			$this->_size = $size;
		}

		/**
		 * @return string|null
		 */
		public function getSrc()
		{
			return $this->_src;
		}

		/**
		 *    Specifies the URL of the image to use as a submit button (only for type="image")
		 *
		 * @param string $src
		 */
		public function setSrc( $src )
		{
			$this->_src = $src;
		}

		/**
		 * @return mixed
		 */
		public function getStep()
		{
			return $this->_step;
		}

		/**
		 * Specifies the legal number intervals for an input field
		 *
		 * @param mixed $step
		 */
		public function setStep( $step )
		{
			$this->_step = $step;
		}

		/**
		 * @return mixed
		 */
		public function getWidth()
		{
			return $this->_width;
		}

		/**
		 *    Specifies the width of an <input> element (only for type="image")
		 *
		 * @param mixed $width Pixels
		 */
		public function setWidth( $width )
		{
			$this->_width = $width;
		}

		/**
		 * @return mixed
		 */
		public function getHeight()
		{
			return $this->_height;
		}

		/**
		 *    Specifies the height of an <input> element (only for type="image")
		 *
		 * @param mixed $height Pixels
		 */
		public function setHeight( $height )
		{
			$this->_height = $height;
		}
	}