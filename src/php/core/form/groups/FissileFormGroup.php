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
	 * This is a **advanced** type of FormGroup that is fissionable.<br/>
	 * Meaning it can be multiplicate itself.
	 *
	 * While the parent class FormGroup generates the value from it's children - FissileFormGroup work very differently because it's fissionable.
	 * If you manipulate the value of the children of FissileFormGroup, you then need to use {@link saveValue()} after manipulation.
	 *
	 * @package tutomvc
	 */
	class FissileFormGroup extends FormGroup
	{
		const BUTTON_NAME_ADD_BEFORE = "_tutomvc_fissile_form_group_add_before";
		const BUTTON_NAME_ADD_AFTER  = "_tutomvc_fissile_form_group_add_after";
		const BUTTON_NAME_DELETE     = "_tutomvc_fissile_form_group_delete";
		const INPUT_INDEX            = "_tutomvc_fissile_form_group_index";
		const INPUT_DELETE           = "_tutomvc_fissile_form_group_delete";
		protected $_max             = 1;
		protected $_min             = 1;
		protected $_includeFallback = TRUE;
		private   $_isSingle        = TRUE;

		/**
		 * @param $name
		 * @param null $title
		 * @param null $description
		 * @param int $min Minimum clones
		 * @param int $max Maximum clones
		 */
		function __construct( $name, $title = NULL, $description = NULL, $min = 1, $max = 1 )
		{
			parent::__construct( $name, $title, $description );
			$this->setMin( $min );
			$this->setMax( $max );
			$this->setIndex( 0 );
		}

		public function parse( $dataArray )
		{
			if ( isset( $dataArray[ $this->getName() ] ) )
			{
				$dataArray = $dataArray[ $this->getName() ];

				$this->setFissions( $dataArray );

				return TRUE;
			}

			return FALSE;
		}

		public function createCloneAt( $index = 0 )
		{

		}

		public function count()
		{
			$fissions = $this->getFissions();

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

		final public function getElement()
		{
			$output = '<ul class="list-group fissile-form-group" id="' . $this->getID() . '">';
			$output .= $this->getHeaderElement();
			for ( $i = 0; $i < $this->count(); $i ++ )
			{
				$output .= $this->getSingleElement( $i );
			}
			if ( !$this->hasReachedMax() ) $output .= $this->getFooterElement();
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

		protected function getSingleElement( $index = 0 )
		{
			$this->setIndex( $index );
			parent::setValue( NULL ); // Just in case
			parent::setValue( $this->getFissionAt( $index ) );
//			parent::validate();
			// Hack to fix child names
			$this->_isSingle = FALSE;
			$output          = '<div class="list-group-item ui-sortable fissile-form-group-item">';
			if ( $this->getMin() == 1 && $this->getMax() == 1 )
			{
				$output .= parent::getFormElement();
			}
			else
			{
				$output .= '
				<li class="list-group-item list-group-item-info fissile-form-group-item-header" style="text-align: right;cursor:move;">
					' . $this->getSingleElementIndexSelector( $index ) . '
				</li>
				<li class="list-group-item fissile-form-group-item-body">
					' . parent::getFormElement() . '
				</li>';
			}
			$output          .= '</div>';
			$this->_isSingle = TRUE;

			return $output;
		}

		protected function getSingleElementIndexSelector( $index )
		{
			$output = "";
//			$output = '<div class="row">';
			//			$output .= '<div class="col-xs-6">';
			$output .= '<label class="btn btn-danger btn-sm">
							<input name="' . $this->formatRootElementName( $index ) . '[' . self::BUTTON_NAME_DELETE . ']" type="checkbox" style="margin:0 6px 0 0;"> <span class="glyphicon glyphicon-remove"></span>
						</label>';
			$output .= '<input type="hidden" value="' . $index . '" class="fissile-form-group-index" name="' . $this->formatRootElementName( $index ) . '[' . self::INPUT_INDEX . ']" >';
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

		public function getHeaderElement()
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
			if ( !$this->hasReachedMax() ) $output .= $this->getTopNavElement();

			return $output;
		}

		protected function getTopNavElement()
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

		public function getFooterElement()
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
			return $this->count() >= $this->getMax() && $this->getMax() >= 0;
		}

		/**
		 * Do not use. Use setMin() and setMax() instead.
		 *
		 * @param bool $value
		 *
		 * @see setMax() setMin()
		 * @throws \ErrorException
		 */
		public function setSingle( $value )
		{
			throw new \ErrorException( "This method cannot be called, it will always be set to true.", 0, E_ERROR );
		}

		/**
		 * @return int
		 */
		public function getMax()
		{
			return $this->_max;
		}

		/**
		 * Max amount of duplications.
		 * A int lower than one equals to unlimited.
		 *
		 * @param int $max
		 */
		public function setMax( $max )
		{
			if ( $max < 1 ) $max = - 1;

			$this->_max = $max;
		}

		/**
		 * @return int
		 */
		public function getMin()
		{
			return $this->_min;
		}

		/**
		 * Minimum amount of duplications.
		 * A int lower than one equals to unlimited.
		 *
		 * @param int $min
		 */
		public function setMin( $min )
		{
			if ( $min < 1 ) $min = - 1;

			$this->_min = $min;

		}

		/**
		 * Saves current value of the child elements to a specific index.
		 *
		 * @param null|int $atIndex If no index is set, it will save it to current index.
		 */
		public function saveFission( $atIndex = NULL )
		{
			if ( !is_array( $this->_value ) ) $this->_value = array();
			if ( is_null( $atIndex ) || !is_int( $atIndex ) ) $atIndex = $this->getIndex();

			$this->_value[ $atIndex ] = parent::getValue();
		}

		public function setValueByFission( $index )
		{
			parent::setValue( $this->getFissionAt( $index ) );
		}

		public function setFissions( $value )
		{
			if ( !is_array( $value ) && !is_null( $value ) && !is_int( $value ) && !is_bool( $value ) )
			{
				throw new \ErrorException( "Expect false, array, int or null.", 0, E_ERROR );
			}

			if ( is_null( $value ) || $value === FALSE )
			{
				parent::setValue( NULL );
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
					parent::setValue( NULL );
					array_unshift( $this->_value, parent::getValue() );
				}
				if ( array_key_exists( self::BUTTON_NAME_ADD_AFTER, $value ) && !$this->hasReachedMax() )
				{
					parent::setValue( NULL );
					$this->_value[] = parent::getValue();
				}
			}
			else if ( is_int( $value ) )
			{
				$this->_value = array();
				$i            = 0;
				while( $i < $value )
				{
					parent::setValue( NULL );
					array_unshift( $this->_value, parent::getValue() );
					$i ++;
				}
			}
			else
			{
				$this->_value = NULL;
			}

		}

		public function getFissions( $call_user_func = NULL )
		{
			$value = empty( $this->_value ) ? $this->getDefaultValue() : $this->_value;

			if ( !is_array( $value ) ) $value = array();

			if ( count( $value ) < $this->getMin() && is_array( $this->getDefaultValue() ) )
			{
				$value = array_merge( $value, $this->getDefaultValue() );
			}
			// Still lacking, need to produce some fake data
			if ( count( $value ) < $this->getMin() )
			{
				$defaultValue = array();
				$before       = parent::getValue();
				parent::setValue( NULL );
				for ( $i = 0; $i < $this->getMin(); $i ++ )
				{
					$defaultValue[] = parent::getValue();
				}
				parent::setValue( $before );
				$value = array_merge( $value, $defaultValue );
			}
			if ( $this->getMax() > 0 && count( $value ) > $this->getMax() )
			{
				// Splice off
				$value = array_slice( $value, 0, $this->getMax() );
			}

			$dp = array();
			if ( is_array( $value ) && count( $value ) )
			{
				$before = parent::getValue();
				foreach ( $value as $index => $valueClone )
				{
					parent::setValue( NULL ); // JUST IN CASE
					parent::setValue( $valueClone );
					$dp[] = parent::getValue( $call_user_func );
				}
				parent::setValue( $before );
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
				$fissions = $this->getFissions();
				$value    = parent::getValue();

				foreach ( $fissions as $key => $fission )
				{
					parent::setValue( NULL ); // Just in case
					$this->setIndex( $key );
					parent::setValue( $fission );
					$valueMap[] = parent::getValueMapAt( $key );
				}

				// Restore value
				parent::setValue( NULL );
				parent::setValue( $value );

				return $valueMap;
			}
			else
			{
				return parent::getValueMapAt( $index );
			}
		}

		/**
		 * @param callable|null $call_user_func
		 *
		 * @return array
		 * @throws \ErrorException
		 */
		public function getFissionsFlatten( $call_user_func = NULL )
		{
			$flatValue    = array();
			$currentValue = $this->getFissions( $call_user_func );
			/** @var FormElement $formElement */
			foreach ( $currentValue as $key => $value )
			{
				$this->setIndex( $key );
				parent::setValue( NULL ); // JUST IN CASE
				parent::setValue( $value );
				$flatValue[ $key ] = parent::getFlatValue( $call_user_func );
			}

			return $flatValue;
		}

		public function getFissionAt( $index = 0 )
		{
			$fissions = $this->getFissions();

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
			$before = parent::getValue();
			for ( $i = 0; $i < $count; $i ++ )
			{
				parent::setValue( $this->getFissionAt( $i ) );
				$fissionErrors = parent::getErrors();
				if ( is_array( $fissionErrors ) ) $errors[ $i ] = $fissionErrors;
			}

			parent::setValue( $before );

			if ( count( $errors ) ) return $errors;
			else return NULL;
		}
	}