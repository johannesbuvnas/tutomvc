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

		/* SET AND GET */
		function getFormElement()
		{
			$output = "";

			$attr = array(
				"name"      => $this->getElementName(),
				"id"        => $this->getID(),
				"class"     => "form-control selectpicker",
				"data-size" => $this->getSize()
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
	}