<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 08/05/15
	 * Time: 10:19
	 */

	namespace tutomvc;

	/**
	 * Class Select2FormInput
	 *
	 * @package tutomvc
	 * @see https://select2.github.io/
	 */
	class Select2FormInput extends SelectFormInput
	{

		protected $_select2Options = array();

		function getFormElement()
		{
			$output = "";

			$attr = array(
				"name"             => $this->getElementName(),
				"id"               => $this->getID(),
				"class"            => "form-control",
				"data-placeholder" => $this->getPlaceholder(),
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

			if ( array_key_exists( "tags", $this->_select2Options ) && filter_var( $this->_select2Options[ "tags" ], FILTER_VALIDATE_BOOLEAN ) )
			{
				foreach ( (array)$this->getValue() as $value )
				{
					if ( !array_key_exists( $value, $this->_optionTitleMap ) ) $this->addOption( $value, $value );
				}
			}

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

			$output .= '
			<script type="text/javascript">
				jQuery("#' . $this->getID() . '").select2( ' . json_encode( $this->getSelect2Options() ) . ' );
			</script>
			';

			return $output;
		}

		/**
		 * @return array
		 */
		public function getSelect2Options()
		{
			return $this->_select2Options;
		}

		/**
		 * This will be parsed as JSON object to the select2-factory method.
		 *
		 * @param array $select2Options
		 *
		 * @see https://select2.github.io/examples.html
		 */
		public function setSelect2Options( $select2Options )
		{
			$this->_select2Options = $select2Options;
		}
	}