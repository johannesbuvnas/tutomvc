<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 16/01/15
	 * Time: 17:54
	 */

	namespace tutomvc;

	/**
	 * Class ClonableFormGroup
	 * A clonable FormGroup.
	 * TODO: Make the form completely independent of JS. Add buttons before and after, delete buttons and index-selectors should be part of the total form.
	 * TODO: Create an autoparse function that respects the add-, delete-, and index buttons.
	 * TODO: Add buttons should be able to add more than one at a time?
	 * @package tutomvc
	 */
	class ClonableFormGroup extends FormGroup
	{
		const BUTTON_NAME_ADD_BEFORE = "_tutomvc_clonable_form_group_add_before";
		const BUTTON_NAME_ADD_AFTER  = "_tutomvc_clonable_form_group_add_after";
		const BUTTON_NAME_DELETE     = "_tutomvc_clonable_form_group_delete";
		const INPUT_INDEX_SELECTOR   = "_tutomvc_clonable_form_group_index_selector";
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
		 * @param bool $includeFallback
		 */
		function __construct( $name, $title = NULL, $description = NULL, $min = 1, $max = 1 )
		{
			parent::__construct( $name, $title, $description );
			$this->setMin( $min );
			$this->setMax( $max );
			$this->setIndex( 0 );
		}

		public function count()
		{
			return is_array( $this->getValue() ) ? count( $this->getValue() ) : 0;
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
						if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
						{
							return $formElement->getValueMapAt( $index );
						}
						else if ( is_a( $formElement, "\\tutomvc\\FormElement" ) )
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

		final public function getFormElement()
		{
			$collection = array();
			$model      = array(
				"max"         => $this->getMax(),
				"min"         => $this->getMin(),
				"label"       => $this->getLabel(),
				"description" => $this->getDescription()
			);
			parent::setValue( NULL );
			$collectionModelDummy = array(
				"name"            => $this->getName(),
				"index"           => 1,
				"total"           => 0,
				"formElementHTML" => parent::getFormElement()
			);
			$output               = '<ul class="list-group clonable-form-group">';
			$output .= $this->getHeaderElement();
			for ( $i = 0; $i < $this->count(); $i ++ )
			{
				parent::setValue( $this->getValueAt( $i ) );
				$output .= $this->getSingleFormElement( $i );
			}
			if ( !$this->hasReachedMax() ) $output .= $this->getFooterElement();
			$output .= '</ul>';

			return $output;
		}

		protected function getSingleFormElement( $index = 0 )
		{
			$this->setIndex( $index );
			// Hack to fix child names
			$this->_isSingle = FALSE;
			$output          = '
			<div class="list-group-item">
				<li class="list-group-item clonable-form-group-item-header disabled">
					' . $this->getSingleFormElementIndexSelector( $index ) . '
				</li>
				<li class="list-group-item clonable-form-group-item-body">
					' . parent::getFormElement() . '
				</li>
			</div>
			';
			$this->_isSingle = TRUE;

			return $output;
		}

		protected function getSingleFormElementIndexSelector( $index )
		{
			$output = '<select class="form-control" name="' . self::INPUT_INDEX_SELECTOR . '_' . $index . '">';
			for ( $i = 0; $i < $this->count(); $i ++ )
			{
				if ( $i == $index ) $output .= '<option selected value="' . $i . '">#' . ($i + 1) . '</option>';
				else $output .= '<option value="' . $i . '">#' . ($i + 1) . '</option>';
			}
			$output .= '</select>';

//			$output .= '<button class="btn btn-sm btn-default"><span class="glyphicon glyphicon-pencil"></span></button>';

			return $output;
		}

		public function getHeaderElement()
		{
			$output = '
					<li class="list-group-item disabled">
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
			$output = '
					<li class="list-group-item clonable-form-group-top-nav" style="text-align: center">
					    <button name="' . self::BUTTON_NAME_ADD_BEFORE . '" class="btn btn-default btn-add" title="' . __( "Clone 1 before", TutoMVC::NAME ) . '">
					        <span class="glyphicon glyphicon-plus"></span>
					    </button>
					</li>';

			return $output;
		}

		public function getFooterElement()
		{
			$output = '
					<li class="list-group-item clonable-form-group-footer" style="text-align: center">
					    <button name="' . self::BUTTON_NAME_ADD_AFTER . '" class="btn btn-default btn-add" title="' . __( "Clone 1 after", TutoMVC::NAME ) . '">
					        <span class="glyphicon glyphicon-plus"></span>
					    </button>
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
		 * @param string $metaBoxName
		 * @param string $inputName
		 * @param int $index
		 *
		 * @return string meta_key
		 */
		public static function constructMetaKey( $metaBoxName, $inputName, $index = 0 )
		{
			return "{$metaBoxName}_{$index}_{$inputName}";
		}

		/**
		 * Do not use. Use setMin and setMax instead.
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

			return $this;
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

			return $this;
		}

		public function setValue( $value )
		{
			if ( !is_array( $value ) && !is_null( $value ) )
			{
				throw new \ErrorException( "Expect array or null.", 0, E_ERROR );
			}

			if ( is_null( $value ) )
			{
				parent::setValue( NULL );
			}

			$this->_value = $value;

			return $this;
		}

		public function getValue( $call_user_func = NULL )
		{
			$value = empty($this->_value) ? $this->getDefaultValue() : $this->_value;

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
					$defaultValue[ ] = parent::getValue();
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
				foreach ( $value as $index => $valueClone )
				{
					parent::setValue( $valueClone );
					$dp[ ] = parent::getValue( $call_user_func );
				}
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
			if ( empty($index) && filter_var( $index, FILTER_VALIDATE_INT ) === FALSE )
			{
				$valueMap = array();
				$value    = $this->getValue();

				foreach ( $value as $key => $singleValue )
				{
					parent::setValue( $singleValue );
					$valueMap[ ] = parent::getValueMapAt( $key );
				}

				// Restore value
				$this->setValue( $value );

				return $valueMap;
			}
			else
			{
				return parent::getValueMapAt( $index );
			}
		}

		/**
		 * @return array
		 */
		public function getFlatValue()
		{
			$flatValue    = array();
			$currentValue = $this->getValue();
			/** @var FormElement $formElement */
			foreach ( $currentValue as $key => $value )
			{
				$this->setIndex( $key );
				parent::setValue( $value );
				$flatValue[ $key ] = parent::getFlatValue();
			}

			return $flatValue;
		}

		public function getValueAt( $index = 0 )
		{
			return is_array( $this->getValue() ) && array_key_exists( $index, $this->getValue() ) ? $this->getValue()[ $index ] : NULL;
		}

		public function setIndex( $index )
		{
			parent::setIndex( $index );

			return $this;
		}

		public function getNameAsParent()
		{
			$name = $this->hasParent() ? "[" . $this->getName() . "]" : $this->getName();

			return $this->_parentName . $name . "[" . $this->getIndex() . "]";
		}
	}