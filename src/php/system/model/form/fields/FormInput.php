<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 10:02
	 */

	namespace tutomvc;

	class FormInput extends Form
	{
		const TYPE_TEXT     = "text";
		const TYPE_PASSWORD = "password";

		protected $_type;
		protected $_readOnly;
		protected $_placeholder;

		function __construct( $name, $title, $description = NULL, $type = FormInput::TYPE_TEXT, $readonly = FALSE, $placeholder = "" )
		{
			$this->setName( $name );
			$this->setTitle( $title );
			$this->setDescription( $description );
			$this->setType( $type );
			$this->setReadOnly( $readonly );
			$this->setPlaceholder( $placeholder );
		}

		function getFormElement()
		{
			$output = "";

			$attr = array(
				"value"       => $this->getValue(),
				"type"        => $this->getType(),
				"placeholder" => $this->getPlaceholder(),
				"name"        => $this->getName(),
				"id"          => $this->getName(),
				"class"       => "form-control"
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

		public function filterValue( $value )
		{
			return $value;
		}

		/**
		 * @param string $value
		 */
		public function setValue( $value )
		{
			parent::setValue( $value );
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
		}
	}