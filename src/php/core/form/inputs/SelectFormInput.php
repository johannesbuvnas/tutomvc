<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 16/01/15
	 * Time: 11:40
	 */

	namespace tutomvc;

	class SelectFormInput extends FormInput
	{
		protected $_options        = array();
		protected $_optionTitleMap = array();

		function __construct( $name, $title, $description = NULL, $single = TRUE, $readonly = FALSE, $placeholder = "" )
		{
			parent::__construct( $name, $title, $description, NULL, $readonly, $placeholder, $single );
		}

		public function addOption( $label, $value, $groupLabel = NULL, $title = NULL )
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

			$this->_optionTitleMap[ $value ] = !empty($title) ? $title : $label;

			return $this;
		}

		public function getOptions()
		{
			return $this->_options;
		}

		function getFormElement()
		{
			$output = "";

			$attr = array(
				"name"  => $this->getElementName(),
				"id"    => $this->getID(),
				"class" => "form-control"
			);
			if ( $this->isReadOnly() ) $attr[ "disabled" ] = "true";
			if ( strlen( $this->getPlaceholder() ) ) $attr[ "title" ] = $this->getPlaceholder();
			if ( !$this->isSingle() )
			{
				$attr[ "multiple" ] = "true";
			}
			$attributes = "";
			foreach ( $attr as $key => $label )
			{
				$attributes .= ' ' . $key . '="' . $label . '"';
			}

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
			return $this->isValueSet( $value ) ? '<option title="' . $this->_optionTitleMap[ $value ] . '" value="' . $value . '" selected>' . $label . '</option>' : '<option title="' . $this->_optionTitleMap[ $value ] . '" value="' . $value . '">' . $label . '</option>';
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
			if ( $this->isSingle() && is_array( parent::getValue() ) ) $value = array_pop( parent::getValue() );
			else $value = parent::getValue();

			if ( !is_null( $call_user_func ) ) return call_user_func_array( $call_user_func, array(&$this, $value) );
			else return $value;
		}

		public function isValueSet( $value )
		{
			if ( is_string( $this->getValue() ) && $this->getValue() == $value ) return TRUE;

			return is_array( $this->getValue() ) ? in_array( $value, $this->getValue() ) : FALSE;
		}
	}