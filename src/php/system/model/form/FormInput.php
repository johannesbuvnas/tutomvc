<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 09:27
	 */

	namespace tutomvc;

	class FormInput extends ValueObject
	{
		private $_title;
		private $_description;
		private $_rules = array();

		public function addRule( Rule $rule )
		{
			$this->_rules[ ] = $rule;
		}

		/* SET AND GET */
		public function setTitle( $title )
		{
			$this->_title = $title;
		}

		public function getTitle()
		{
			return $this->_title;
		}

		public function setDescription( $description )
		{
			$this->_description = $description;
		}

		public function getDescription()
		{
			return $this->_description;
		}

		public function getRules()
		{
			return $this->_rules;
		}

		public function getLabelElement()
		{
			return '<label for="' . $this->getName() . '">' . $this->getTitle() . '</label>';
		}

		public function getDescriptionElement()
		{
			return '<span class="help-block">' . $this->getDescription() . '</span>';
		}

		public function getFormElement()
		{
			return '';
		}
	}