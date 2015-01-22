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
	 * @package tutomvc
	 */
	class ClonableFormGroup extends FormGroup
	{
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
		function __construct( $name, $title = NULL, $description = NULL, $min = 1, $max = 1, $includeFallback = TRUE )
		{
			parent::__construct( $name, $title, $description );
			$this->setMin( $min );
			$this->setMax( $max );
			$this->setIncludeFallback( $includeFallback );
			$this->setIndex( 0 );
		}

		public function count()
		{
			return is_array( $this->getValue() ) ? count( $this->getValue() ) : 0;
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
//			$count                = $this->count();
//			if ( $this->getMax() > 0 && $this->count() > $this->getMax() ) $count = $this->getMax();
//			if ( $count < $this->getMin() ) $count = $this->getMin();
			$output = "";
			$output = '<ul class="list-group reproducible-form-group">';
			$output .= $this->getHeaderElement();
			$outputFallback = "";
			$outputFallback .= '<div class="hidden no-js-fallback">';
			for ( $i = 0; $i < $this->count(); $i ++ )
			{
				parent::setValue( NULL );
				if ( $i < $this->count() )
				{
					parent::setValue( $this->getValueAt( $i ) );
				}
				$collection[ ] = array(
					"name"            => $this->getName(),
					//					"index" => $i,
					//					"total" => $count,
					"formElementHTML" => parent::getFormElement()
				);

				$this->setIndex( $i );
				// Hack to fix child names for the fallback output
				$this->_isSingle = FALSE;
				$outputFallback .= parent::getFormElement();
				$this->_isSingle = TRUE;
			}
			$outputFallback .= '</div>';
			if ( $this->getIncludeFallback() ) $output .= $outputFallback;
			$output .= '<textarea class="hidden model">' . json_encode( $model, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ) . '</textarea>';
			$output .= '<textarea class="hidden collection">' . json_encode( $collection, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ) . '</textarea>';
			parent::setValue( NULL );
			$output .= '<textarea class="hidden collection-dummy-model">' . json_encode( $collectionModelDummy, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ) . '</textarea>';

			$output .= '</ul>';

			return $output;
		}

		public function getHeaderElement()
		{
			return '
					<li class="list-group-item">
						<h3>
							' . $this->getLabel() . '
							<small class="help-block">
								' . $this->getDescription() . '
							</small>
						</h3>
					</li>
			';
		}

		public function getFormElementByElementName( $elementName )
		{
			$index = FormElement::extractAncestorIndex( $elementName );
			$this->setIndex( $index );

			return parent::getFormElementByElementName( $elementName );
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
		 * Will always return true. Use
		 * @return bool
		 * @see getMin() getMax()
		 */
		public function isSingle()
		{
			return $this->_isSingle;
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

		/**
		 * @return boolean
		 */
		public function getIncludeFallback()
		{
			return $this->_includeFallback;
		}

		/**
		 * @param boolean $outputFallback
		 */
		public function setIncludeFallback( $outputFallback )
		{
			$this->_includeFallback = $outputFallback;
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

		public function getValue()
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

			$this->_value = $value;

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