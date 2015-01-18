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
		protected $_filterEnabled = FALSE;
		protected $_tagEnabled    = FALSE;

		protected $_options = array();

		function __construct( $name, $title, $description = NULL, $single = TRUE, $readonly = FALSE, $placeholder = "" )
		{
			parent::__construct( $name, $title, $description, NULL, $readonly, $placeholder, $single );
		}

		public function addOption( $name, $value, $groupLabel = NULL )
		{
			if ( is_string( $groupLabel ) )
			{
				if ( !array_key_exists( $groupLabel, $this->_options ) || !is_array( $this->_options[ $groupLabel ] ) ) $this->_options[ $groupLabel ] = array();
				$this->_options[ $groupLabel ][ $value ] = $name;
			}
			else
			{
				$this->_options[ $value ] = $name;
			}

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
				"name"      => $this->getElementName(),
				"id"        => $this->getID(),
				"class"     => "form-control selectpicker",
				"data-size" => "auto"
			);
			if ( $this->isReadOnly() ) $attr[ "disabled" ] = "true";
			if ( strlen( $this->getPlaceholder() ) ) $attr[ "title" ] = $this->getPlaceholder();
			if ( !$this->isSingle() )
			{
				$attr[ "multiple" ] = "true";
			}
			if ( $this->isFilterEnabled() ) $attr[ "data-live-search" ] = "true";
			if ( $this->isTagEnabled() )
			{
				$attr[ "data-live-search" ] = "true";
				$attr[ "data-tag-enabled" ] = "true";
			}

			$attributes = "";
			foreach ( $attr as $key => $value )
			{
				$attributes .= ' ' . $key . '="' . $value . '"';
			}

			$output .= '
					<select ' . $attributes . '>
				';
			foreach ( $this->getOptions() as $key => $value )
			{
				if ( is_array( $value ) )
				{
					$output .= '
					<optgroup label="' . $key . '">
					';
					foreach ( $value as $groupOptionValue => $groupOptionName )
					{
						$output .= $this->getOptionElement( $groupOptionName, $groupOptionValue );
					}
					$output .= '
					</optgroup>
					';
				}
				else
				{
					$output .= $this->getOptionElement( $value, $key );
				}
			}
			$output .= '
					</select>
				';

			return $output;
		}

		protected function getOptionElement( $name, $value )
		{
			return $this->hasValue( $value ) ? '<option value="' . $value . '" selected>' . $name . '</option>' : '<option value="' . $value . '">' . $name . '</option>';
		}

		/**
		 * @return boolean
		 */
		public function isFilterEnabled()
		{
			return $this->_filterEnabled;
		}

		/**
		 * @param boolean $_filterEnabled
		 */
		public function setFilterEnabled( $_filterEnabled )
		{
			$this->_filterEnabled = $_filterEnabled;

			return $this;
		}

		/**
		 * @return boolean
		 */
		public function isTagEnabled()
		{
			return $this->_tagEnabled;
		}

		/**
		 * @param boolean $tagEnabled
		 */
		public function setTagEnabled( $tagEnabled )
		{
			$this->_tagEnabled = $tagEnabled;

			return $this;
		}

		/**
		 * @param array|null $value
		 */
		public function setValue( $value )
		{
			if ( !is_array( $value ) && !is_null( $value ) )
			{
				throw new \ErrorException( "Expect array or null.", 0, E_ERROR );
			}

			return parent::setValue( $value );
		}

		/**
		 * @return array|null
		 */
		public function getValue()
		{
			return parent::getValue();
		}

		public function hasValue( $value )
		{
			return is_array( $this->_value ) ? in_array( $value, $this->_value ) : FALSE;
		}
	}