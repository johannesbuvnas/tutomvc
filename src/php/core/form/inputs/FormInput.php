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

		function getFormElement()
		{
			$output = "";

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

			$attributes = "";
			foreach ( $attr as $key => $value )
			{
				$attributes .= ' ' . $key . '="' . $value . '"';
			}

			$output .= '
					<input ' . $attributes . ' />
				';

			return $output;
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
		 */
		public function setAutocomplete( $autocomplete )
		{
			$this->_autocomplete = $autocomplete;

			return $this;
		}
	}