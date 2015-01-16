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
		const TYPE_DEFAULT  = "type_default";
		const TYPE_MULTIPLE = "type_multiple";

		protected $_filterEnabled = FALSE;
		protected $_tagEnabled    = FALSE;

		protected $_options = array();

		function __construct( $name, $title, $description = NULL, $type = SelectFormInput::TYPE_DEFAULT, $readonly = FALSE, $placeholder = "" )
		{
			parent::__construct( $name, $title, $description, $type, $readonly, $placeholder );
		}

		public function addOption( $name, $value, $groupLabel = NULL )
		{
			if ( is_string( $groupLabel ) )
			{
				if ( !array_key_exists( $groupLabel, $this->_options ) || !is_array( $this->_options[ $groupLabel ] ) ) $this->_options[ $groupLabel ] = [];
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
				"name"      => $this->getName() . "[]",
				"id"        => $this->getName(),
				"class"     => "form-control selectpicker",
				"data-size" => "auto"
			);
			if ( $this->isReadOnly() ) $attr[ "disabled" ] = "true";
			if ( !empty($this->getPlaceholder()) ) $attr[ "title" ] = $this->getPlaceholder();
			if ( $this->getType() == self::TYPE_MULTIPLE )
			{
				$attr[ "multiple" ]         = "true";
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
			return $this->getValue() == $value ? '<option value="' . $value . '" selected>' . $name . '</option>' : '<option value="' . $value . '">' . $name . '</option>';
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
	}