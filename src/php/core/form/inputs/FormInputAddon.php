<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/01/15
	 * Time: 20:21
	 */

	namespace tutomvc\core\form\inputs;

	use tutomvc\core\form\FormElement;

	class FormInputAddon extends FormElement
	{
		function __construct( $label )
		{
			parent::__construct( NULL, NULL );
			$this->setLabel( $label );
		}

		public function getFormElement()
		{
			return '
			<div class="input-group-addon">' . $this->getLabel() . '</div>
			';
		}

		public function getName()
		{
			return $this->getLabel();
		}
	}