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

		protected $_select2Options = array(
			"theme" => "classic"
		);

		function getFormElement()
		{
			$output = "";

			$attributes = $this->getFormElementAttributesAsString();

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

			return $output;
		}

		function getFormElementAttributes()
		{
			$attr = parent::getFormElementAttributes();

			foreach ( $this->getSelect2Options() as $key => $value )
			{
				$attr[ "data-$key" ] = htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' );
			}

			$attr[ 'class' ]        = $attr[ 'class' ] . " select2";

			return $attr;
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