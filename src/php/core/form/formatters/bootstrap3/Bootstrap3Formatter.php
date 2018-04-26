<?php

	namespace tutomvc\core\form\formatters\bootstrap3;

	use tutomvc\core\form\formatters\IFormElementFormatter;
	use tutomvc\core\form\FormElement;
	use tutomvc\core\form\groups\FissileFormGroup;
	use tutomvc\core\form\groups\FormGroup;
	use tutomvc\core\form\groups\FormInputGroup;
	use tutomvc\core\form\inputs\CheckBoxFormInput;
	use tutomvc\core\form\inputs\FormInput;

	class Bootstrap3Formatter implements IFormElementFormatter
	{
		const CSS_CLASS = "";
		protected $_fissileFormatter;

		public function __construct()
		{
			$this->_fissileFormatter = new FissileBoostrap3Formatter();
		}

		public function formatOutput( FormElement $el )
		{
			if ( $el instanceof FormInput )
			{
				if ( $el->getType() == FormInput::TYPE_HIDDEN ) return $this->formatDefaultOutput( $el, array("hidden") );
			}
			else if ( $el instanceof FissileFormGroup )
			{
				return $this->_fissileFormatter->formatOutput( $el );
			}

			return $this->formatDefaultOutput( $el );
		}

		public function formatDefaultOutput( FormElement $el, $classNames = array() )
		{
			$classNames = array_merge( array(
				"form-group"
			), $classNames );
			if ( is_string( $el->getErrorMessage() ) ) $classNames[] = "has-error";
			$output = '<div class="' . implode( " ", $classNames ) . '">';
			$output .= $el->formatHeaderOutput();
			$output .= $el->formatErrorMessageOutput();
			$output .= $el->formatFormElementOutput();
			$output .= $el->formatFooterOutput();
			$output .= '</div>';

			return $output;
		}

		public function formatHeaderOutput( FormElement $el )
		{
			if ( $el instanceof CheckBoxFormInput )
			{
				return '<div class="checkbox"><label class="control-label" for="' . $el->getID() . '">' . $this->formatInputElementOutput( $el ) . " " . $el->getLabel() . '</label></div>';
			}
			else if ( $el instanceof FormInput )
			{
				return $el->getType() == FormInput::TYPE_HIDDEN ? '' : '<label class="control-label" for="' . $el->getID() . '">' . $el->getLabel() . '</label>';
			}
			else if ( $el instanceof FormGroup )
			{
				return '
					<header>
						<h2>
							' . $el->getLabel() . '
							<small class="help-block">
								' . $el->getDescription() . '
							</small>
						</h2>
					</header>
					';
			}
			else if ( $el instanceof FissileFormGroup )
			{
				return $this->_fissileFormatter->formatHeaderOutput( $el );
			}

			return '';
		}

		public function formatFooterOutput( FormElement $el )
		{
			if ( $el instanceof FissileFormGroup )
			{
				return $this->_fissileFormatter->formatFooterOutput( $el );
			}
			else if ( $el instanceof FormInput )
			{
				if ( $el->getType() == FormInput::TYPE_HIDDEN ) return '';
			}
			else if ( $el instanceof FormGroup )
			{
				return '<hr/>';
			}

			return $this->formatDefaultFooterOutput( $el );
		}

		public function formatDefaultFooterOutput( FormElement $el )
		{
			$desc = $el->getDescription();

			return is_string( $desc ) && strlen( $desc ) ? '<span class="help-block">' . $desc . '</span>' : '';
		}

		public function formatErrorMessageOutput( FormElement $el )
		{
			if ( $el instanceof FormInputGroup )
			{
				$output = '';

				$output .= $this->formatDefaultErrorMessageOutput( $el );

				foreach ( $el->getMap() as $formElement )
				{
					$output .= $this->formatDefaultErrorMessageOutput( $formElement );
				}

				return $output;
			}
			else if ( $el instanceof FissileFormGroup )
			{
				return $this->_fissileFormatter->formatErrorMessageOutput( $el );
			}

			return $this->formatDefaultErrorMessageOutput( $el );
		}

		protected function formatDefaultErrorMessageOutput( FormElement $el )
		{
			$errorMsg = $el->getErrorMessage();
			if ( is_string( $errorMsg ) )
			{
				return '<div class="alert alert-danger" role="alert">' . $errorMsg . '</div>';
			}

			return '';
		}

		public function formatFormElementOutput( FormElement $el )
		{
			if ( $el instanceof CheckBoxFormInput )
			{
				return '';
			}
			else if ( $el instanceof FormInput )
			{
				return $this->formatInputElementOutput( $el );
			}
			else if ( $el instanceof FormGroup )
			{
				$output = '<div class="form-group" id="' . $el->getID() . '">';
				foreach ( $el->getMap() as $child )
				{
					$output .= $child->formatOutput();
				}
				$output .= '</div>';

				return $output;
			}
			else if ( $el instanceof FissileFormGroup )
			{
				return "";
			}

			return '';
		}

		protected function formatInputElementOutput( FormElement $el )
		{
			$output = "";

			$output .= '
					<input ' . $el->getFormElementAttributesAsString() . ' />
				';

			return $output;
		}
	}