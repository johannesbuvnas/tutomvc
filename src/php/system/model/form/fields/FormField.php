<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 10:02
	 */

	namespace tutomvc;

	class FormField extends FormInput
	{
		private $_defaultValue = NULL;

		function __construct( $name, $title, $description = NULL )
		{
			$this->setName( $name );
			$this->setTitle( $name );
			$this->setDescription( $name );
		}

		/**
		 * @return null
		 */
		public function getDefaultValue()
		{
			return $this->_defaultValue;
		}

		/**
		 * @param null $defaultValue
		 */
		public function setDefaultValue( $defaultValue )
		{
			$this->_defaultValue = $defaultValue;
		}
	}