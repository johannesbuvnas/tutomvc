<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 16/01/15
	 * Time: 11:40
	 */

	namespace TutoMVC\Form\Input;

	use TutoMVC\Form\Input\FormInput;
	use function array_key_exists;

	/**
	 * A select element.
	 *
	 * @package tutomvc\core\form\inputs
	 */
	class SelectFormInput extends FormInput
	{
		protected $_options            = array();
		protected $_optionsLabelMap    = array();
		protected $_optionsDisabledMap = array();
		protected $_optionTitleMap     = array();

		/**
		 * SelectFormInput constructor.
		 *
		 * @param string $name
		 * @param $title
		 * @param null $description
		 * @param bool $single
		 * @param bool $readonly
		 * @param string $placeholder
		 */
		function __construct( $name, $title, $description = NULL, $single = TRUE, $readonly = FALSE, $placeholder = "" )
		{
			parent::__construct( $name, $title, $description, NULL, $readonly, $placeholder, $single );
		}

		/**
		 * @param $label
		 * @param $value
		 * @param null $groupLabel
		 * @param null $title
		 * @param bool $disabled
		 *
		 * @return $this
		 */
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
			$this->_optionTitleMap[ $value ]     = !empty( $title ) ? $title : $label;
			$this->_optionsLabelMap[ $value ]    = $label;

			return $this;
		}

		public function getOptionLabel( $optionValue )
		{
			return array_key_exists( $optionValue, $this->_optionsLabelMap ) ? $this->_optionsLabelMap[ $optionValue ] : NULL;
		}

		/**
		 * Remove single option.
		 *
		 * @param $optionValue
		 */
		public function removeOption( $optionValue )
		{
			if ( array_key_exists( $optionValue, $this->_options ) )
			{
				unset( $this->_options[ $optionValue ] );
				unset( $this->_optionsDisabledMap[ $optionValue ] );
				unset( $this->_optionTitleMap[ $optionValue ] );
				unset( $this->_optionsLabelMap[ $optionValue ] );
			}
			foreach ( $this->_options as $groupLabel => $groupLabelArray )
			{
				if ( is_array( $groupLabelArray ) )
				{
					if ( array_key_exists( $optionValue, $groupLabelArray ) )
					{
						unset( $groupLabelArray[ $optionValue ] );
					}
				}
			}
		}

		/**
		 * Remove all options.
		 */
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

		function formatFormElementOutput()
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

		/**
		 * Generates the option HTML element.
		 *
		 * @param $label
		 * @param $value
		 *
		 * @return string
		 */
		public function getOptionElement( $label, $value )
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

		public function isOptionDisabled( $optionValue )
		{
			return array_key_exists( $optionValue, $this->_optionsDisabledMap );
		}

		public function setDefaultValue( $value )
		{
			if ( !is_string( $value ) && !is_array( $value ) && !is_null( $value ) ) $value = strval( $value );
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
			if ( !is_string( $value ) && !is_array( $value ) && !is_null( $value ) ) $value = strval( $value );
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

		/**
		 * @param string $value
		 *
		 * @return bool
		 */
		public function isValueSet( $value )
		{
			if ( is_string( $this->getValue() ) && strcmp( strtolower( $this->getValue() ), strtolower( $value ) ) == 0 ) return TRUE;

			return is_array( $this->getValue() ) ? in_array( $value, $this->getValue() ) : FALSE;
		}
	}
