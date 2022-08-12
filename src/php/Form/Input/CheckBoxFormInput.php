<?php
	namespace TutoMVC\Form\Input;

	class CheckBoxFormInput extends FormInput
	{
		protected bool $_switchMode = FALSE;

		function __construct( $name, $title, $description = NULL, $readonly = FALSE )
		{
			parent::__construct( $name, $title, $description, self::TYPE_CHECKBOX, $readonly, NULL, TRUE );
		}

		function getFormElementAttributes()
		{
			$attr = parent::getFormElementAttributes();
			if ( array_key_exists( "value", $attr ) ) unset( $attr[ "value" ] );
			$attr[ "class" ] = "form-input-element";
			if ( $this->isSwitchMode() )
			{
				$attr[ 'role' ] = "switch";
			}

			return $attr;
		}

		/**
		 * @return bool
		 */
		public function isSwitchMode(): bool
		{
			return $this->_switchMode;
		}

		/**
		 * @param bool $switchMode
		 */
		public function setSwitchMode( bool $switchMode ): void
		{
			$this->_switchMode = $switchMode;
		}
	}
