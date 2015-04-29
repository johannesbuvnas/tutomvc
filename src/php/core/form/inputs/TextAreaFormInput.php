<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/01/15
	 * Time: 20:55
	 */

	namespace tutomvc;

	class TextAreaFormInput extends FormInput
	{
		protected $_rows = 5;

		public function __construct( $name, $title, $description = NULL, $readonly = FALSE, $placeholder = "", $rows = 5 )
		{
			parent::__construct( $name, $title, $description, NULL, $readonly, $placeholder, TRUE );
			$this->setRows( $rows );
		}

		function getFormElement()
		{
			$output = "";

			$attr = array(
				"placeholder" => $this->getPlaceholder(),
				"name"        => $this->getElementName(),
				"id"          => $this->getID(),
				"class"       => "form-control",
				"rows"        => $this->getRows()
			);
			if ( $this->isReadOnly() ) $attr[ "readonly" ] = "true";
			if ( $this->getType() == self::TYPE_HIDDEN ) $attr[ 'class' ] .= " hidden";

			$attributes = "";
			foreach ( $attr as $key => $value )
			{
				$attributes .= ' ' . $key . '="' . $value . '"';
			}

			$output .= '<textarea ' . $attributes . '>' . $this->getValue() . '</textarea>';

			return $output;
		}

		/**
		 * @return int
		 */
		public function getRows()
		{
			return $this->_rows;
		}

		/**
		 * @param int $rows
		 */
		public function setRows( $rows )
		{
			$this->_rows = $rows;
		}
	}