<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 16/01/15
	 * Time: 17:54
	 */

	namespace tutomvc;

	class ObjectMetaBox extends FormGroup
	{
		protected $_max      = 1;
		protected $_min      = 1;
		private   $_isSingle = TRUE;

		final public function getWPFormElement( $value = array() )
		{
			$collection           = array();
			$model                = array(
				"max"         => $this->getMax(),
				"min"         => $this->getMin(),
				"label"       => $this->getLabel(),
				"description" => $this->getDescription()
			);
			$this->setValue( NULL );
			$collectionModelDummy = array(
				"name"            => $this->getName(),
				"index"           => 1,
				"total"           => 0,
				"formElementHTML" => $this->getFormElement()
			);

			if ( !is_array( $value ) ) $value = array();
			$count = count( $value );
			if ( $this->getMax() > 0 && $count > $this->getMax() ) $count = $this->getMax();
			if ( $count < $this->getMin() ) $count = $this->getMin();
			$output = "";
			$output = '<ul class="list-group metabox-list-group" data-max="' . $this->getMax() . '" data-min="' . $this->getMin() . '">';
			$output .= $this->getHeaderElement();
			$output .= '<div class="hidden no-js-fallback">';
			for ( $i = 0; $i < $count; $i ++ )
			{
				$this->setValue( NULL );
				if ( $i < count( $value ) ) $this->setValue( $value[ $i ] );
				$collection[ ] = array(
					"name"            => $this->getName(),
					//					"index" => $i,
					//					"total" => $count,
					"formElementHTML" => $this->getFormElement()
				);

				$this->setIndex( $i );
				// Hack to fix child names for the fallback output
				$this->_isSingle = FALSE;
				$output .= $this->getFormElement();
				$this->_isSingle = TRUE;
			}

			$output .= '</div>';
			$output .= '<textarea class="hidden model">' . json_encode( $model ) . '</textarea>';
			$output .= '<textarea class="hidden collection">' . json_encode( $collection ) . '</textarea>';
			$this->setValue( NULL );
			$output .= '<textarea class="hidden collection-dummy-model">' . json_encode( $collectionModelDummy ) . '</textarea>';

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

		public function getIndexSelectElement( $count )
		{
			$output = '
			<select class="selectpicker" data-width="auto" name="' . $this->constructFormElementChildName( "#" ) . '" title="' . __( "Position", TutoMVC::NAME ) . '">
			';
			$count  = $count + 1;
			for ( $i = 1; $i < $count; $i ++ )
			{
				if ( $i == $this->getIndex() ) $output .= '<option selected value="' . $i . '">#' . $i . '</option>';
				else $output .= '<option value="' . $i . '">#' . $i . '</option>';
			}
			$output .= '</select>';

			return $output;
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
	}