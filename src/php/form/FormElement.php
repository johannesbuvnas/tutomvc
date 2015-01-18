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
		protected $_label;
		protected $_id;
		protected $_description;
		protected $_rules  = array();
		protected $_single = TRUE;
		protected $_index  = NULL;
		protected $_defaultValue;

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

		/**
		 * @return mixed
		 */
		public function getDefaultValue()
		{
			return $this->_defaultValue;
		}

		public function getValue()
		{
			$value = parent::getValue();

			return empty($value) ? $this->getDefaultValue() : $value;
		}

		/**
		 * @param mixed $defaultValue
		 */
		public function setDefaultValue( $defaultValue )
		{
			$this->_defaultValue = $defaultValue;

			return $this;
		}
	}