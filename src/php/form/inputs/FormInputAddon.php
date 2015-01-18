<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/01/15
	 * Time: 20:21
	 */

	namespace tutomvc;

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
	}