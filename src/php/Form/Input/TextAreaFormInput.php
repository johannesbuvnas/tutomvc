<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/01/15
	 * Time: 20:55
	 */

	namespace TutoMVC\Form\Input;

	use TutoMVC\Form\Input\FormInput;

	/**
	 * Textarea form element.
	 * @package tutomvc\core\form\inputs
	 */
	class TextAreaFormInput extends FormInput
	{
		protected $_rows = 5;

		public function __construct( $name, $title, $description = NULL, $readonly = FALSE, $placeholder = "", $rows = 5 )
		{
			parent::__construct( $name, $title, $description, NULL, $readonly, $placeholder, TRUE );
			$this->setRows( $rows );
		}

		function formatFormElementOutput()
		{
			$output = "";

			$output .= '
					<textarea ' . $this->getFormElementAttributesAsString() . ' >' . $this->getValue() . '</textarea>
				';

			return $output;
		}

		function getFormElementAttributes()
		{
			$attr            = parent::getFormElementAttributes();
			$attr[ 'value' ] = NULL;
			$attr[ 'rows' ]  = $this->getRows();

			return $attr;
		}

		/**
		 * @return int
		 */
		public function getRows()
		{
			return $this->_rows;
		}

		/**
		 * Will add the rows-attr. It's the height attribute of textareas.
		 *
		 * @param int $rows
		 */
		public function setRows( $rows )
		{
			$this->_rows = $rows;
		}
	}
