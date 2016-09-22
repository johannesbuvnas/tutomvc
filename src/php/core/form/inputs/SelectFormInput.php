<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 16/01/15
	 * Time: 11:40
	 */

	namespace tutomvc\core\form\inputs;

	class SelectFormInput extends FormInput
	{
		protected $_options            = array();
		protected $_optionsDisabledMap = array();
		protected $_optionTitleMap     = array();

		function __construct( $name, $title, $description = NULL, $single = TRUE, $readonly = FALSE, $placeholder = "" )
		{
			parent::__construct( $name, $title, $description, NULL, $readonly, $placeholder, $single );
		}

		public function addOption( $label, $value, $groupLabel = NULL, $title = NULL, $disabled = FALSE )
		{
			if ( is_string( $groupLabel ) )
			{
				if ( !array_key_exists( $groupLabel, $this->_options ) || !is_array( $this->_options[ $groupLabel ] ) ) $this->_options[ $groupLabel ] = array();
				$this->_options[ $groupLabel ][ $value ] = $label;
			}
			else
			{
				$this->_options[ $value ] = $label;
			}

			$this->_optionsDisabledMap[ $value ] = $disabled;
			$this->_optionTitleMap[ $value ]     = !empty($title) ? $title : $label;

			return $this;
		}

		/**
		 * @param $optionValue
		 */
		public function removeOption( $optionValue )
		{
			if ( array_key_exists( $optionValue, $this->_options ) )
			{
				unset($this->_options[ $optionValue ]);
				unset($this->_optionsDisabledMap[ $optionValue ]);
				unset($this->_optionTitleMap[ $optionValue ]);
			}
			foreach ( $this->_options as $groupLabel => $groupLabelArray )
			{
				if ( is_array( $groupLabelArray ) )
				{
					if ( array_key_exists( $optionValue, $groupLabelArray ) )
					{
						unset($groupLabelArray[ $optionValue ]);
					}
				}
			}
		}

		public function removeOptions()
		{
			$this->_options            = array();
			$this->_optionsDisabledMap = array();
			$this->_optionTitleMap     = array();
		}

		/**
		 * @return array
		 */
		public function getOptions()
		{
			return $this->_options;
		}

		function getFormElement()
		{
			$output = "";

			$attributes = $this->getFormElementAttributesAsString();

			$output .= '
					<select ' . $attributes . '>
				';
			foreach ( $this->getOptions() as $key => $label )
			{
				if ( is_array( $label ) )
				{
					$output .= '
					<optgroup label="' . $key . '">
					';
					foreach ( $label as $groupOptionValue => $groupOptionLabel )
					{
						$output .= $this->getOptionElement( $groupOptionLabel, $groupOptionValue );
					}
					$output .= '
					</optgroup>
					';
				}
				else
				{
					$output .= $this->getOptionElement( $label, $key );
				}
			}
			$output .= '
					</select>
				';

			return $output;
		}

		protected function getOptionElement( $label, $value )
		{
			$attr = array(
				"value" => $value,
				"title" => $this->_optionTitleMap[ $value ]
			);
			if ( $this->isValueSet( $value ) ) $attr[ "selected" ] = "";
			if ( $this->_optionsDisabledMap[ $value ] ) $attr[ "disabled" ] = "";

			$attributes = "";
			foreach ( $attr as $key => $attrValue )
			{
				$attributes .= ' ' . $key . '="' . $attrValue . '"';
			}

			return '<option ' . $attributes . '>' . $label . '</option>';
		}

		public function setDefaultValue( $value )
		{
			if ( is_string( $value ) ) $value = array($value);
			if ( !is_array( $value ) && !is_null( $value ) )
			{
				throw new \ErrorException( "Expect array or null.", 0, E_ERROR );
			}

			return parent::setDefaultValue( $value );
		}

		/**
		 * @param array|null $value
		 *
		 * @return $this
		 * @throws \ErrorException
		 */
		public function setValue( $value )
		{
			if ( is_string( $value ) ) $value = array($value);
			if ( !is_array( $value ) && !is_null( $value ) )
			{
				throw new \ErrorException( "Expect array or null.", 0, E_ERROR );
			}

			return parent::setValue( $value );
		}

		/**
		 * @param null|callback $call_user_func
		 *
		 * @return array|null
		 */
		public function getValue( $call_user_func = NULL )
		{
			$parentValue = parent::getValue();
			if ( $this->isSingle() && is_array( $parentValue ) ) $value = array_pop( $parentValue );
			else $value = parent::getValue();

			if ( !is_null( $call_user_func ) ) return call_user_func_array( $call_user_func, array($this, $value) );
			else return $value;
		}

		public function isValueSet( $value )
		{
			if ( is_string( $this->getValue() ) && strcmp( $this->getValue(), $value ) == 0 ) return TRUE;

			return is_array( $this->getValue() ) ? in_array( $value, $this->getValue() ) : FALSE;
		}
	}