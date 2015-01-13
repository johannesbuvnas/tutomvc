<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 09:13
	 */

	namespace tutomvc;

	class FormSection extends FormInput
	{
		private $_fieldMap = array();


		public function addField( FormField $metaField )
		{
			$this->_fieldMap[ $metaField->getName() ] = $metaField;

			return $metaField;
		}

		public function hasField( $name )
		{
			return array_key_exists( $name, $this->_fieldMap );
		}

		/* SET AND GET */
		public function getFields()
		{
			return $this->_fieldMap;
		}

		public function getField( $name )
		{
			if ( array_key_exists( $name, $this->_fieldMap ) )
			{
				return $this->_fieldMap[ $name ];
			}
			else
			{
				return NULL;
			}
		}
	}