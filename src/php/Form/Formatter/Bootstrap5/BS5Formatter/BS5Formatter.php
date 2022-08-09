<?php

	namespace TutoMVC\Form\Formatter\Bootstrap5\BS5Formatter;

	use TutoMVC\Form\Formatter\Bootstrap5\BS5FissileFormmatter;
	use TutoMVC\Form\Formatter\IFormElementFormatter;
	use TutoMVC\Form\FormElement;
	use TutoMVC\Form\Group\FissileFormGroup;
	use TutoMVC\Form\Group\FormGroup;
	use TutoMVC\Form\Input\CheckBoxFormInput;
	use TutoMVC\Form\Input\FormInput;
	use TutoMVC\Form\Input\SelectFormInput;
	use function array_key_exists;
	use function array_merge;
	use function get_class;
	use function implode;
	use function in_array;
	use function is_array;
	use function is_string;
	use function str_ireplace;
	use function strlen;

	class BS5Formatter implements IFormElementFormatter
	{
		protected       $_fissileFormatter;
		protected array $_spacingClasses         = array("mb-3");
		protected array $_floatingInputClassList = array();

		public function __construct()
		{
			$this->_fissileFormatter = new BS5FissileFormmatter();
//			$this->registerClassReferenceAsFloatingInput( FormInput::class );
//			$this->registerClassReferenceAsFloatingInput( SelectFormInput::class );
		}

		public function registerClassReferenceAsFloatingInput( string $classReference )
		{
			$this->_floatingInputClassList[] = $classReference;
		}

		public function isClassReferenceRegisteredAsFloatingInput( string $classReference )
		{
			return in_array( $classReference, $this->_floatingInputClassList );
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
			if ( $this->isClassReferenceRegisteredAsFloatingInput( get_class( $el ) ) )
			{
				return $this->formatFloatingInput( $el, $classNames );
			}

			$classNames = array_merge( array(), $classNames );
			if ( is_string( $el->getErrorMessage() ) ) $classNames[] = "is-invalid";
			$classNames = array_merge( $classNames, $this->_spacingClasses );
			$output     = '<div class="' . implode( " ", $classNames ) . '">';
			$output     .= $el->formatHeaderOutput();
			$output     .= $el->formatErrorMessageOutput();
			$output     .= $el->formatFormElementOutput();
			$output     .= $el->formatFooterOutput();
			$output     .= '</div>';

			return $output;
		}

		public function formatFloatingInput( FormElement $el, $classNames = array() )
		{
			$classNames = array_merge( $classNames );
			if ( is_string( $el->getErrorMessage() ) ) $classNames[] = "is-invalid";
			$classNames   = array_merge( $classNames, $this->_spacingClasses );
			$classNames[] = "form-floating";
			$output       = '<div class="' . implode( " ", $classNames ) . '">';
			$output       .= $el->formatFormElementOutput( $el );
			$output       .= $this->formatLabel( $el );
			$output       .= $el->formatErrorMessageOutput();
			$output       .= $el->formatFooterOutput();
			$output       .= '</div>';

			return $output;
		}

		public function formatHeaderOutput( FormElement $el )
		{
			if ( $el instanceof FormGroup )
			{
				return '
					<fieldset>
						<legend>
							' . $el->getLabel() . '
						</legend>
						<p class="form-text">
								' . $el->getDescription() . '
							</p>
					</fieldset>
					';
			}
			else if ( $el instanceof FissileFormGroup )
			{
				return $this->_fissileFormatter->formatHeaderOutput( $el );
			}
			else
			{
				return $this->formatLabel( $el );
			}

			return '';
		}

		public function formatLabel( FormElement $el )
		{
			if ( $el instanceof CheckBoxFormInput )
			{
				return '<div class="form-check form-switch">' . $this->formatInputElementOutput( $el ) . '<label class="form-check-label" for="' . $el->getID() . '">' . $el->getLabel() . '</label></div>';
			}
			else if ( $el instanceof FormInput )
			{
				return $el->getType() == FormInput::TYPE_HIDDEN ? '' : '<label for="' . $el->getID() . '">' . $el->getLabel() . '</label>';
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

			return is_string( $desc ) && strlen( $desc ) ? '<div class="form-text">' . $desc . '</div>' : '';
		}

		public function formatErrorMessageOutput( FormElement $el )
		{
			if ( $el instanceof FissileFormGroup )
			{
				return $this->_fissileFormatter->formatErrorMessageOutput( $el );
			}

			return $this->formatDefaultErrorMessageOutput( $el );
		}

		/**
		 * @param FormElement $el
		 *
		 * @return string
		 */
		function getFormElementAttributesAsString( $el )
		{
			$attr = $el->getFormElementAttributes();

			if ( !array_key_exists( "placeholder", $attr ) )
			{
				$attr[ 'placeholder' ] = $el->getLabel();
			}
			if ( $el->hasError() )
			{
				$attr[ 'class' ]            = $attr[ 'class' ] . " is-invalid";
				$attr[ 'aria-describedby' ] = $el->getID() . "-feedback";
			}

			if ( $el instanceof FormInput && $el->isReadOnly() )
			{
				$attr[ 'class' ] = str_ireplace( "form-control", "", $attr[ 'class' ] );
				$attr[ 'class' ] = $attr[ 'class' ] . " form-control-plaintext";
			}

			if ( $el instanceof CheckBoxFormInput )
			{
				$attr[ 'class' ] = $attr[ 'class' ] . " form-check-input";
			}
			$attributes = "";
			foreach ( $attr as $key => $value )
			{
				$attributes .= ' ' . $key . '="' . $value . '"';
			}

			return $attributes;
		}

		protected function formatDefaultErrorMessageOutput( FormElement $el )
		{
			$errorMsg = $el->getErrorMessage();
			if ( is_string( $errorMsg ) )
			{
				return '<div id="' . $el->getID() . "-feedback" . '" class="invalid-feedback">' . $errorMsg . '</div>';
			}

			return '';
		}

		public function formatFormElementOutput( FormElement $el )
		{
			if ( $el instanceof CheckBoxFormInput )
			{
				return '';
			}
			else if ( $el instanceof SelectFormInput )
			{
				return $this->formatSelectOutput( $el );
			}
			else if ( $el instanceof FormInput )
			{
				return $this->formatInputElementOutput( $el );
			}
			else if ( $el instanceof FormGroup )
			{
				$output = '<div class="" id="' . $el->getID() . '">';
				foreach ( $el->getMap() as $child )
				{
					$output .= $child->formatOutput();
				}
				$output .= '</div>';

				return $output;
			}
			else if ( $el instanceof FissileFormGroup )
			{
				$this->_fissileFormatter->formatFormElementOutput( $el );
			}

			return '';
		}

		protected function formatInputElementOutput( FormElement $el )
		{
			$output = "";

			$output .= '
					<input ' . $this->getFormElementAttributesAsString( $el ) . ' />
				';

			return $output;
		}

		private function formatSelectOutput( SelectFormInput $el )
		{
			$output = "";

			$attributes = $this->getFormElementAttributesAsString( $el );

			$output .= '
					<select ' . $attributes . '>
				';
			foreach ( $el->getOptions() as $key => $label )
			{
				if ( is_array( $label ) )
				{
					$output .= '
					<optgroup label="' . $key . '">
					';
					foreach ( $label as $groupOptionValue => $groupOptionLabel )
					{
						$output .= $el->getOptionElement( $groupOptionLabel, $groupOptionValue );
					}
					$output .= '
					</optgroup>
					';
				}
				else
				{
					$output .= $el->getOptionElement( $label, $key );
				}
			}
			$output .= '
					</select>
				';

			return $output;
		}

		/**
		 * @return array|string[]
		 */
		public function getSpacingClasses(): array
		{
			return $this->_spacingClasses;
		}

		/**
		 * @param array|string[] $spacingClasses
		 */
		public function setSpacingClasses( array $spacingClasses ): void
		{
			$this->_spacingClasses = $spacingClasses;
		}
	}
