<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 07/05/15
	 * Time: 13:06
	 *
	 */

	namespace tutomvc;

	/**
	 * Class SelectPickerFormInput
	 * Dependent on JS.
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
			if ( array_key_exists( $value, $this->_optionsSubtextMap ) ) $attr[ "data-subtext" ] = $this->_optionsSubtextMap[ $value ];
			if ( array_key_exists( $value, $this->_optionsContentMap ) ) $attr[ "data-content" ] = $this->_optionsContentMap[ $value ];

			$attributes = "";
			foreach ( $attr as $key => $attrValue )
			{
				$attributes .= ' ' . $key . '="' . $attrValue . '"';
			}

			return '<option ' . $attributes . '>' . $label . '</option>';
		}

		function getFormElement()
		{
			$output = "";

			$attr = array(
				"name"              => $this->getElementName(),
				"id"                => $this->getID(),
				"class"             => "form-control selectpicker",
				"data-size"         => $this->getSize(),
				"data-show-subtext" => $this->isShowSubtext()
			);
			if ( $this->isReadOnly() ) $attr[ "disabled" ] = "true";
			if ( strlen( $this->getPlaceholder() ) ) $attr[ "title" ] = $this->getPlaceholder();
			if ( !$this->isSingle() )
			{
				$attr[ "multiple" ] = "true";
			}
			if ( $this->isLiveSearchEnabled() ) $attr[ "data-live-search" ] = "true";

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
		 * @return string
		 */
		public function getSize()
		{
			return $this->_size;
		}

		/**
		 * @param string $size
		 *
		 * @return $this
		 * @see http://silviomoreto.github.io/bootstrap-select/#size
		 */
		public function setSize( $size )
		{
			$this->_size = $size;

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