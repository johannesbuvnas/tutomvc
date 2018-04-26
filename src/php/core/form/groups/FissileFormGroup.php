<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 16/01/15
	 * Time: 17:54
	 */

	namespace tutomvc\core\form\groups;

	use tutomvc\core\form\ElementNameExtractor;
	use tutomvc\core\form\FormElement;

	/**
	 * This is a fissible FormGroup without factoring more instances of the same class.
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
		protected $_cachedFissionIndex;
		protected $_cachedFissionValue;

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
		 * @throws \ErrorException
		 */
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

		public function resetFissions()
		{
			$this->reset();
			$this->setFissions( NULL );
		}

		public function count()
		{
			$fissions = $this->getFissions();

			return is_array( $fissions ) ? count( $fissions ) : 0;
		}

		public function findByElementName( $elementName )
		{
			$elementName = self::sanitizeName( $elementName );
			$index       = self::extractAncestorIndex( $elementName );

			if ( !is_null( $index ) )
			{
				$indexBefore = $index;
				$this->setIndex( $index );
				$formElement = parent::findByElementName( $elementName );
				$this->setIndex( $indexBefore );

				return $formElement;
			}

			return NULL;
		}

		public function getKeyMapByElementName( $elementName )
		{
			$extractor = new ElementNameExtractor( $elementName );

			if ( $extractor->getAncestor() == $this->getElementName() )
			{
				$oldIndex = $this->getIndex();
				$children = $extractor->getChildren();
				if ( is_array( $children ) && count( $children ) )
				{
					$formElement = $this->findByElementName( $extractor->getElementName() );
					if ( $formElement instanceof FormGroup )
					{
						$this->setIndex( $extractor->getIndex() );
						$map = $formElement->getKeyMap();
						$this->setIndex( $oldIndex );

						return $map;
					}
					else if ( $formElement instanceof FormElement )
					{
						return $formElement->getElementName();
					}
				}
				else
				{
					$this->setIndex( $extractor->getIndex() );
					$map = $this->getKeyMap();
					$this->setIndex( $oldIndex );

					return $map;
				}
			}
			else if ( $extractor->getElementName() == $this->getElementName() )
			{
				return $this->getFissionKeyMap();
			}

			return FALSE;
		}

		/**
		 * @param $index
		 *
		 * @throws \ErrorException
		 */
		public function switchToFission( $index )
		{
			$this->setIndex( $index );
			$this->setValue( NULL );
			$this->setValue( $this->getFissionAt( $index ) );
		}

		/**
		 * Store current values to fissions at the specified index.
		 *
		 * @param null|int $atIndex Current index if NULL
		 */
		public function storeFission( $atIndex = NULL )
		{
			if ( !is_array( $this->_value ) ) $this->_value = array();
			if ( is_null( $atIndex ) || !is_int( $atIndex ) ) $atIndex = $this->getIndex();

			$this->_value[ $atIndex ] = $this->getValue();
		}

		/**
		 * Saves current fission index to cache.
		 */
		protected function cacheFission()
		{
			$this->_cachedFissionIndex = $this->getIndex();
			$this->_cachedFissionValue = $this->getValue();
		}

		/**
		 * Switch current value to fission index in cache.
		 */
		protected function switchToCachedFission()
		{
			$this->setValue( NULL );
			$this->setIndex( $this->_cachedFissionIndex );
			$this->setValue( $this->_cachedFissionValue );
		}

		final public function formatOutput()
		{
			$output = '<ul class="list-group fissile-form-group" id="' . $this->getID() . '">';
			$output .= $this->formatHeaderOutput();
			for ( $i = 0; $i < $this->count(); $i ++ )
			{
				$output .= $this->formatFissionOutput( $i );
			}
			if ( !$this->hasReachedMaxFissions() ) $output .= $this->formatFooterOutput();
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
			$this->cacheFission();
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

			$this->switchToCachedFission();

			return $output;
		}

		protected function formatFissionIndexSelectorOutput( $index )
		{
			$output = "";
			$output .= '<label class="btn btn-danger btn-sm">
							<input name="' . $this->formatRootElementName( $index ) . '[' . self::BUTTON_NAME_DELETE . ']" type="checkbox" style="margin:0 6px 0 0;"> <span class="glyphicon glyphicon-remove"></span>
						</label>';

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
			if ( !$this->hasReachedMaxFissions() ) $output .= $this->getTopNavOutput();

			return $output;
		}

		protected function getTopNavOutput()
		{
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

		public function hasReachedMaxFissions()
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

		public function setFissions( $value )
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
				if ( array_key_exists( self::BUTTON_NAME_ADD_BEFORE, $value ) && !$this->hasReachedMaxFissions() )
				{
					$this->setValue( NULL );
					array_unshift( $this->_value, $this->getValue() );
				}
				if ( array_key_exists( self::BUTTON_NAME_ADD_AFTER, $value ) && !$this->hasReachedMaxFissions() )
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

		public function getFissions( $call_user_func = NULL )
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
				$this->cacheFission();
				$this->setValue( NULL );
				for ( $i = 0; $i < $this->getMinimumFissions(); $i ++ )
				{
					$defaultValue[] = $this->getValue();
				}
				$this->switchToCachedFission();
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
				$this->cacheFission();
				foreach ( $value as $index => $valueClone )
				{
					$this->setValue( NULL );
					$this->setIndex( $index );
					$this->setValue( $valueClone );
					$dp[] = $this->getValue( $call_user_func );
				}
				$this->switchToCachedFission();
			}

			if ( !is_null( $call_user_func ) ) $this->_value = call_user_func_array( $call_user_func, array(
				&$this,
				$dp
			) );
			else $this->_value = $dp;

			return $this->_value;
		}

		public function getFissionKeyMap()
		{
			$map      = array();
			$oldIndex = $this->getIndex();
			$fissions = $this->getFissions();

			$this->cacheFission();

			foreach ( $fissions as $fissionIndex => $value )
			{
				$this->setValue( NULL );
				$this->setIndex( $fissionIndex );
				$this->setValue( $value );
				$map[] = $this->getKeyMap( $fissionIndex );
			}

			$this->setIndex( $oldIndex );

			return $map;
		}

		/**
		 * @param callable|null $call_user_func
		 *
		 * @return array
		 * @throws \ErrorException
		 */
		public function getFlatFissions( $call_user_func = NULL )
		{
			$this->cacheFission();
			$flatValue    = array();
			$currentValue = $this->getFissions( $call_user_func );
			/** @var FormElement $formElement */
			foreach ( $currentValue as $fissionIndex => $value )
			{
				$this->setValue( NULL );
				$this->setIndex( $fissionIndex );
				$this->setValue( $value );
				$flatValue = array_merge( $flatValue, $this->getFlatValue( $call_user_func ) );
			}
			$this->switchToCachedFission();

			return $flatValue;
		}

		public function getFissionAt( $index = 0 )
		{
			$fissions = $this->getFissions();

			return is_array( $fissions ) && array_key_exists( $index, $fissions ) ? $fissions[ $index ] : NULL;
		}

		public function getFlatFissionErrors()
		{
			$this->cacheFission();
			$errors       = array();
			$currentValue = $this->getFissions();
			foreach ( $currentValue as $fissionIndex => $value )
			{
				$this->setValue( NULL );
				$this->setIndex( $fissionIndex );
				$this->setValue( $value );
				$errors = array_merge( $errors, $this->getFlatErrors() );
			}

			$this->switchToCachedFission();

			if ( count( $errors ) ) return $errors;
			else return NULL;
		}

		public function getFissionErrors()
		{
			$this->cacheFission();
			$errors       = array();
			$currentValue = $this->getFissions();
			foreach ( $currentValue as $fissionIndex => $value )
			{
				$this->setValue( NULL );
				$this->setIndex( $fissionIndex );
				$this->setValue( $value );
				$fissionErrors = $this->getErrors();
				if ( is_array( $fissionErrors ) ) $errors[ $fissionIndex ] = $fissionErrors;
			}

			$this->switchToCachedFission();

			if ( count( $errors ) ) return $errors;
			else return NULL;
		}
	}