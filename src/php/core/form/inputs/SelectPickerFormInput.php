<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 07/05/15
	 * Time: 13:06
	 *
	 */

	namespace tutomvc\core\form\inputs;

	/**
	 * Advanced select-element with search ability. Dependant on external javascript library. Good dropdown with ability to customize.
	 *
	 * @package tutomvc
	 * @see http://silviomoreto.github.io/bootstrap-select/
	 */
	class SelectPickerFormInput extends SelectFormInput
	{
		protected $_liveSearchEnabled = FALSE;
		protected $_size              = "auto";
		protected $_optionsSubtextMap = array();
		protected $_optionsContentMap = array();
		protected $_showSubtext       = TRUE;

		/* SET AND GET */
		public function setOptionSubtext( $optionValue, $subtext = "" )
		{
			$this->_optionsSubtextMap[ $optionValue ] = $subtext;

			return $this;
		}

		public function setOptionContent( $optionValue, $content )
		{
			$this->_optionsContentMap[ $optionValue ] = $content;

			return $this;
		}

		protected function getOptionElement( $label, $value )
		{
			$attr = array(
				"value" => $value,
			);
			if ( $this->isValueSet( $value ) ) $attr[ "selected" ] = "";
			if ( $this->_optionsDisabledMap[ $value ] ) $attr[ "disabled" ] = "";
			if ( array_key_exists( $value, $this->_optionsSubtextMap ) ) $attr[ "data-subtext" ] = $this->_optionsSubtextMap[ $value ];
			if ( array_key_exists( $value, $this->_optionsContentMap ) ) $attr[ "data-content" ] = $this->_optionsContentMap[ $value ];

			$attributes = "";
			foreach ( $attr as $key => $attrValue )
			{
				$attributes .= ' ' . $key . '="' . $attrValue . '"';
			}

			return '<option ' . $attributes . '>' . $label . '</option>';
		}

		function getFormElementAttributes()
		{
			$attr                        = parent::getFormElementAttributes();
			$attr[ "data-size" ]         = $this->getSize();
			$attr[ "data-show-subtext" ] = $this->isShowSubtext();
			$attr[ "class" ]             = $attr[ "class" ] . " selectpicker";
			if ( $this->isReadOnly() ) $attr[ "disabled" ] = "true";
			if ( $this->isLiveSearchEnabled() ) $attr[ "data-live-search" ] = "true";

			unset($attr[ "size" ]);

			return $attr;
		}

		function formatFormElementOutput()
		{
			$output = "";

			$output .= '
					<select ' . $this->getFormElementAttributesAsString() . '>
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
		 * @return boolean
		 */
		public function isLiveSearchEnabled()
		{
			return $this->_liveSearchEnabled;
		}

		/**
		 * @param boolean $_liveSearchEnabled
		 *
		 * @return $this
		 */
		public function setLiveSearchEnabled( $_liveSearchEnabled )
		{
			$this->_liveSearchEnabled = $_liveSearchEnabled;

			return $this;
		}

		/**
		 * @return boolean
		 */
		public function isShowSubtext()
		{
			return $this->_showSubtext;
		}

		/**
		 * @param boolean $showSubtext
		 */
		public function setShowSubtext( $showSubtext )
		{
			$this->_showSubtext = $showSubtext;
		}
	}