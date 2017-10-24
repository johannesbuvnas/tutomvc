<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 16/01/15
	 * Time: 17:54
	 */

	namespace tutomvc\core\form\groups;

	use tutomvc\core\form\FormElement;

	/**
	 * FissileFormGroup is a FormGroup that can clone itself - like a fission - but without factoring more instances of the same class.
	 * FissileFormGroup just clones the value map.
	 *
	 * @package tutomvc\core\form\groups
	 */
	class FissileFormGroup extends FormGroup
	{
		const BUTTON_NAME_ADD_BEFORE = "_tutomvc_fissile_form_group_add_before";
		const BUTTON_NAME_ADD_AFTER  = "_tutomvc_fissile_form_group_add_after";
		const BUTTON_NAME_DELETE     = "_tutomvc_fissile_form_group_delete";
		const INPUT_INDEX            = "_tutomvc_fissile_form_group_index";
		const INPUT_DELETE           = "_tutomvc_fissile_form_group_delete";
		protected $_maximumFissions = 1;
		protected $_minimumFissions = 1;

		/**
		 * @param $name
		 * @param null $title
		 * @param null $description
		 * @param int $minimumFissions Minimum clones. A int lower than one equals to unlimited.
		 * @param int $maximumFissions Maximum clones. A int lower than one equals to unlimited.
		 */
		function __construct( $name, $title = NULL, $description = NULL, $minimumFissions = 1, $maximumFissions = 1 )
		{
			parent::__construct( $name, $title, $description );
			$this->setMinimumFissions( $minimumFissions );
			$this->setMaximumFissions( $maximumFissions );
			$this->setIndex( 0 );
		}

		/**
		 * Expects fission data array.
		 *
		 * @param array $dataArray
		 *
		 * @return bool
		 */
		public function parse( $dataArray )
		{
			if ( isset( $dataArray[ $this->getName() ] ) )
			{
				$dataArray = $dataArray[ $this->getName() ];

				$this->setFissionsValue( $dataArray );

				return TRUE;
			}

			return FALSE;
		}

		public function reset()
		{
			parent::reset();
			$this->setFissionsValue( NULL );
		}

		public function count()
		{
			$fissions = $this->getFissionsValue();

			return is_array( $fissions ) ? count( $fissions ) : 0;
		}

		public function findFormElementByElementName( $elementName )
		{
			$elementName = self::sanitizeName( $elementName );
			$index       = self::extractAncestorIndex( $elementName );
			if ( is_null( $index ) ) return NULL;
			else $this->setIndex( $index );
			$formElement = $this->getFormElementByElementName( $elementName );

			/** @var FormElement $formElement */
			if ( $formElement ) return $formElement;

			foreach ( $this->getFormElements() as $formElement )
			{
				if ( $formElement instanceof FormGroup )
				{
					/** @var FormGroup $formElement */
					/** @var FormElement $subFormElement */
					$subFormElement = $formElement->findFormElementByElementName( $elementName );
					if ( $subFormElement ) return $subFormElement;
				}
			}

			return NULL;
		}

		public function getValueMapByElementName( $elementName )
		{
			$elementName = FormElement::sanitizeName( $elementName );
			$matches     = FormElement::matchElementName( $elementName );

			if ( count( $matches ) == 4 )
			{
				$ancestor = $matches[ 1 ];
				if ( $ancestor == $this->getElementName() )
				{
					$index    = intval( $matches[ 2 ] );
					$rest     = $matches[ 3 ];
					$children = FormElement::extractGroupNames( $rest );
					if ( is_array( $children ) && count( $children ) )
					{
						$formElement = $this->getFormElementByElementName( $elementName );
						if ( $formElement instanceof FormGroup )
						{
							return $formElement->getValueMapAt( $index );
						}
						else if ( $formElement instanceof FormElement )
						{
							return $formElement->getElementName();
						}
						else
						{
//							return $children;
						}
					}
					else
					{
						return $this->getValueMapAt( $index );
					}
				}
			}
			else if ( $elementName == $this->getElementName() )
			{
				return $this->getValueMapAt( NULL );
			}

			return FALSE;
		}

		public function switchToFission( $index )
		{
			$this->setIndex( $index );
			$this->setValueByFission( $index );
		}

		final public function formatOutput()
		{
			$output = '<ul class="list-group fissile-form-group" id="' . $this->getID() . '">';
			$output .= $this->formatHeaderOutput();
			for ( $i = 0; $i < $this->count(); $i ++ )
			{
				$output .= $this->formatFissionOutput( $i );
			}
			if ( !$this->hasReachedMax() ) $output .= $this->formatFooterOutput();
			$output .= '</ul>';

			$output .= '
			<script type="text/javascript">
			jQuery(document).ready(function()
			{
				jQuery("#' . $this->getID() . '").sortable({
					items: ".fissile-form-group-item",
					opacity: 0.5,
					handle: ".fissile-form-group-item-header",
					tolerance: "pointer",
					update: function( event, ui ) 
					{
						var i = 0;
						jQuery(this).find(".fissile-form-group-item").each(function()
						{
							jQuery(this).find("input.fissile-form-group-index").val(i);
							i++;
						});
					}
				});
			});
			</script>
			';

			return $output;
		}

		protected function formatFissionOutput( $index = 0 )
		{
			$indexBefore = $this->getIndex();

			$this->switchToFission( $index );
			$output = '<div class="list-group-item ui-sortable fissile-form-group-item">';
			$output .= '<input type="hidden" value="' . $index . '" class="fissile-form-group-index" name="' . $this->formatRootElementName( $index ) . '[' . self::INPUT_INDEX . ']" >';
			if ( $this->getMinimumFissions() == 1 && $this->getMaximumFissions() == 1 )
			{
				$output .= $this->formatFormElementOutput();
			}
			else
			{
				$output .= '
				<li class="list-group-item list-group-item-info fissile-form-group-item-header" style="text-align: right;cursor:move;">
					' . $this->formatFissionIndexSelectorOutput( $index ) . '
				</li>
				<li class="list-group-item fissile-form-group-item-body">
					' . $this->formatFormElementOutput() . '
				</li>';
			}
			$output .= '</div>';

			$this->switchToFission( $indexBefore );

			return $output;
		}

		protected function formatFissionIndexSelectorOutput( $index )
		{
			$output = "";
//			$output = '<div class="row">';
			//			$output .= '<div class="col-xs-6">';
			$output .= '<label class="btn btn-danger btn-sm">
							<input name="' . $this->formatRootElementName( $index ) . '[' . self::BUTTON_NAME_DELETE . ']" type="checkbox" style="margin:0 6px 0 0;"> <span class="glyphicon glyphicon-remove"></span>
						</label>';
//			$output .= '</div>';
//			$output .= '<div class="col-xs-5">';
//			$output .= '<select class="pull-right" name="' . $this->formatRootElementName( $index ) . '[' . self::INPUT_INDEX_SELECTOR . ']">';
//			for ( $i = 0; $i < $this->count(); $i ++ )
//			{
//				if ( $i == $index ) $output .= '<option selected value="' . $i . '">#' . ($i + 1) . '</option>';
//				else $output .= '<option value="' . $i . '">#' . ($i + 1) . '</option>';
//			}
//			$output .= '</select>';
//			$output .= '</div>';

//			$output .= '</div>';

			return $output;
		}

		public function formatHeaderOutput()
		{
			$output = '
					<li class="list-group-item">
						<h2>
							' . $this->getLabel() . '
							<small class="help-block">
								' . $this->getDescription() . '
							</small>
						</h2>
					</li>
			';
			if ( !$this->hasReachedMax() ) $output .= $this->getTopNavOutput();

			return $output;
		}

		protected function getTopNavOutput()
		{
//			$output = '
//			<li class="list-group-item fissile-form-group-top-nav disabled" style="text-align: center">
//				<div class="input-group input-sm">
//					<input type="number" value="1" min="1" class="form-control" id="appendedInput">
//					<label class="input-group-addon text-primary has-success">
//						<input name="" type="checkbox"> <span class="glyphicon glyphicon-plus"></span>
//					</label>
//				</div>
//			</li>';

			$output = '
					<li class="list-group-item fissile-form-group-top-nav" style="text-align: center">
					    <label class="btn btn-default">
							<input name="' . $this->formatRootElementName( self::BUTTON_NAME_ADD_BEFORE ) . '" type="checkbox" style="margin:0 6px 0 0;"> <span class="glyphicon glyphicon-plus"></span> 1
						</label>
					</li>';

			return $output;
		}

		public function formatFooterOutput()
		{
			$output = '
					<li class="list-group-item fissile-form-group-footer" style="text-align: center">
						<label class="btn btn-default">
							<input name="' . $this->formatRootElementName( self::BUTTON_NAME_ADD_AFTER ) . '" type="checkbox" style="margin:0 6px 0 0;"> <span class="glyphicon glyphicon-plus"></span> 1
						</label>
					</li>';

			return $output;
		}

		public function getFormElementByElementName( $elementName )
		{
			$index = FormElement::extractAncestorIndex( $elementName );
			$this->setIndex( $index );

			return parent::getFormElementByElementName( $elementName );
		}

		public function hasReachedMax()
		{
			return $this->count() >= $this->getMaximumFissions() && $this->getMaximumFissions() >= 0;
		}

		/**
		 * Do not use. Use setMin() and setMax() instead.
		 *
		 * @param bool $value
		 *
		 * @see setMaximumFissions() setMin()
		 * @throws \ErrorException
		 */
		public function setSingle( $value )
		{
			throw new \ErrorException( "This method cannot be called, it will always be set to true.", 0, E_ERROR );
		}

		/**
		 * @return int
		 */
		public function getMaximumFissions()
		{
			return $this->_maximumFissions;
		}

		/**
		 * Max amount of duplications.
		 * A int lower than one equals to unlimited.
		 *
		 * @param int $value
		 */
		public function setMaximumFissions( $value )
		{
			if ( $value < 1 ) $value = - 1;

			$this->_maximumFissions = $value;
		}

		/**
		 * @return int
		 */
		public function getMinimumFissions()
		{
			return $this->_minimumFissions;
		}

		/**
		 * Minimum amount of duplications.
		 * A int lower than one equals to unlimited.
		 *
		 * @param int $value
		 */
		public function setMinimumFissions( $value )
		{
			if ( $value < 1 ) $value = - 1;

			$this->_minimumFissions = $value;

		}

		/**
		 * Set current value to fissions at the specified index.
		 *
		 * @param null|int $atIndex Current index if NULL
		 */
		public function setCurrentValueToFission( $atIndex = NULL )
		{
			if ( !is_array( $this->_value ) ) $this->_value = array();
			if ( is_null( $atIndex ) || !is_int( $atIndex ) ) $atIndex = $this->getIndex();

			$this->_value[ $atIndex ] = $this->getValue();
		}

		public function setValueByFission( $index )
		{
			$this->setValue( $this->getFissionValueAt( $index ) );
		}

		public function setFissionsValue( $value )
		{
			if ( !is_array( $value ) && !is_null( $value ) && !is_int( $value ) && !is_bool( $value ) )
			{
				throw new \ErrorException( "Expect false, array, int or null.", 0, E_ERROR );
			}

			if ( is_null( $value ) || $value === FALSE )
			{
				$this->setValue( NULL );
				$this->_value = NULL;
			}
			else if ( is_array( $value ) )
			{
				$this->_value = array();
				$newIndex     = 0;
				foreach ( $value as $index => $fission )
				{
					if ( is_int( filter_var( $index, FILTER_VALIDATE_INT ) ) && !array_key_exists( self::BUTTON_NAME_DELETE, $fission ) )
					{
						$this->_value[ $newIndex ] = $fission;
						$newIndex ++;
					}
				}
				if ( array_key_exists( self::BUTTON_NAME_ADD_BEFORE, $value ) && !$this->hasReachedMax() )
				{
					$this->setValue( NULL );
					array_unshift( $this->_value, $this->getValue() );
				}
				if ( array_key_exists( self::BUTTON_NAME_ADD_AFTER, $value ) && !$this->hasReachedMax() )
				{
					$this->setValue( NULL );
					$this->_value[] = $this->getValue();
				}
			}
			else if ( is_int( $value ) )
			{
				$this->_value = array();
				$i            = 0;
				while( $i < $value )
				{
					$this->setValue( NULL );
					array_unshift( $this->_value, $this->getValue() );
					$i ++;
				}
			}
			else
			{
				$this->_value = NULL;
			}
		}

		public function getFissionsValue( $call_user_func = NULL )
		{
			$value = empty( $this->_value ) ? $this->getDefaultValue() : $this->_value;

			if ( !is_array( $value ) ) $value = array();

			if ( count( $value ) < $this->getMinimumFissions() && is_array( $this->getDefaultValue() ) )
			{
				$value = array_merge( $value, $this->getDefaultValue() );
			}
			// Still lacking, need to produce some fake data
			if ( count( $value ) < $this->getMinimumFissions() )
			{
				$defaultValue = array();
				$before       = $this->getValue();
				$this->setValue( NULL );
				for ( $i = 0; $i < $this->getMinimumFissions(); $i ++ )
				{
					$defaultValue[] = $this->getValue();
				}
				$this->setValue( $before );
				$value = array_merge( $value, $defaultValue );
			}
			if ( $this->getMaximumFissions() > 0 && count( $value ) > $this->getMaximumFissions() )
			{
				// Splice off
				$value = array_slice( $value, 0, $this->getMaximumFissions() );
			}

			$dp = array();
			if ( is_array( $value ) && count( $value ) )
			{
				$before = $this->getValue();
				foreach ( $value as $index => $valueClone )
				{
					$this->setValue( NULL ); // JUST IN CASE
					$this->setValue( $valueClone );
					$dp[] = $this->getValue( $call_user_func );
				}
				$this->setValue( $before );
			}

			if ( !is_null( $call_user_func ) ) $this->_value = call_user_func_array( $call_user_func, array(
				&$this,
				$dp
			) );
			else $this->_value = $dp;

			return $this->_value;
		}

		public function getValueMapAt( $index = NULL )
		{
			if ( empty( $index ) && filter_var( $index, FILTER_VALIDATE_INT ) === FALSE )
			{
				$valueMap = array();
				$fissions = $this->getFissionsValue();
				$value    = $this->getValue();

				foreach ( $fissions as $key => $fission )
				{
					$this->setValue( NULL ); // Just in case
					$this->setIndex( $key );
					$this->setValue( $fission );
					$valueMap[] = $this->getValueMapAt( $key );
				}

				// Restore value
				$this->setValue( NULL );
				$this->setValue( $value );

				return $valueMap;
			}
			else
			{
				return $this->getValueMapAt( $index );
			}
		}

		/**
		 * @param callable|null $call_user_func
		 *
		 * @return array
		 * @throws \ErrorException
		 */
		public function getFissionsValueFlatten( $call_user_func = NULL )
		{
			$flatValue    = array();
			$currentValue = $this->getFissionsValue( $call_user_func );
			/** @var FormElement $formElement */
			foreach ( $currentValue as $key => $value )
			{
				$this->setIndex( $key );
				$this->setValue( NULL ); // JUST IN CASE
				$this->setValue( $value );
				$flatValue[ $key ] = $this->getFlatValue( $call_user_func );
			}

			return $flatValue;
		}

		public function getFissionValueAt( $index = 0 )
		{
			$fissions = $this->getFissionsValue();

			return is_array( $fissions ) && array_key_exists( $index, $fissions ) ? $fissions[ $index ] : NULL;
		}

		/**
		 * Returns array of errors if errors exists.
		 * If no errors exists, it returns NULL.
		 * @return array|null
		 */
		public function getErrors()
		{
			$errors = array();
			$count  = $this->count();
			$before = $this->getValue();
			for ( $i = 0; $i < $count; $i ++ )
			{
				$this->setValue( $this->getFissionValueAt( $i ) );
				$fissionErrors = parent::getErrors();
				if ( is_array( $fissionErrors ) ) $errors[ $i ] = $fissionErrors;
			}

			$this->setValue( $before );

			if ( count( $errors ) ) return $errors;
			else return NULL;
		}
	}