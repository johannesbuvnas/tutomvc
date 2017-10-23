<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/01/15
	 * Time: 20:21
	 */

	namespace tutomvc\core\form\inputs;

	use tutomvc\core\form\FormElement;

	/**
	 * Addon for {@link tutomvc\core\form\groups\FormInputGroup}.<br/>
	 * Doesn't have any value, just a visual addon.
	 * @package tutomvc\core\form\inputs
	 * @see http://getbootstrap.com/components/#input-groups-buttons
	 */
	class FormInputAddon extends FormElement
	{
		function __construct( $label )
		{
			parent::__construct( NULL );
			$this->setLabel( $label );
		}

		public function formatFormElementOutput()
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