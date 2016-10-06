<?php
	namespace tutomvc\core\model;

	class ValueObject extends NameObject
	{
		/* VARS */
		protected $_value;

		function __construct( $name, $value )
		{
			parent::__construct( $name );
			$this->setValue( $value );
		}

		/* SET AND GET */
		public function setValue( $value )
		{
			$this->_value = $value;

			return $this;
		}

		public function getValue()
		{
			return $this->_value;
		}
	}