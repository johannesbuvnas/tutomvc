<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 19/01/15
	 * Time: 09:30
	 */

	namespace tutomvc;

	class PostMetaBox extends ReproducibleFormGroup
	{
		private $_metaKeysMap;

		/**
		 * Filter the sanitization of a specific meta key of a specific meta type.
		 *
		 * The dynamic portions of the hook name, `$meta_type`, and `$meta_key`,
		 * refer to the metadata object type (comment, post, or user) and the meta
		 * key value,
		 * respectively.
		 *
		 * @since 3.3.0
		 *
		 * @param mixed $meta_value Meta value to sanitize.
		 * @param string $meta_key Meta key.
		 * @param string $meta_type Meta type.
		 */
		final public function sanitize_meta( $meta_value, $meta_key, $meta_type )
		{
			if ( $meta_key == $this->getName() )
			{
				$meta_value = count( $meta_value );
				// Loop and save all meta
			}

			return $meta_value;
		}

		/**
		 * Filter whether to retrieve metadata of a specific type.
		 *
		 * The dynamic portion of the hook, `$meta_type`, refers to the meta
		 * object type (comment, post, or user). Returning a non-null value
		 * will effectively short-circuit the function.
		 *
		 * @since 3.1.0
		 *
		 * @param null|array|string $value The value get_metadata() should
		 *                                     return - a single metadata value,
		 *                                     or an array of values.
		 * @param int $object_id Object ID.
		 * @param string $meta_key Meta key.
		 * @param string|array $single Meta value, or an array of values.
		 */
		final public function get_metadata( $null, $object_id, $meta_key, $single )
		{
			// Loop through a map of possible meta keys

			return $null;
		}

		public function toMetaKeyVO( $postArray, $at = 0 )
		{
			$vo = $this->mapMetaKeysFromArray( $postArray, $this->getName() . "[$at]" );

			return $vo;
		}

		/**
		 * @return mixed
		 */
		public function getMetaKeysMap()
		{
			if ( !is_array( $this->_metaKeysMap ) )
			{
				$this->_metaKeysMap = $this->mapMetaKeysFromFormGroup( $this );
			}

			return $this->_metaKeysMap;
		}

		/**
		 * @param FormGroup $formGroup
		 */
		private function mapMetaKeysFromFormGroup( $formGroup, $begin = "" )
		{
			$metaKeys = array();
			/** @var FormElement $formElement */
			foreach ( $formGroup->getFormElements() as $formElement )
			{
				if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
				{
					foreach ( $this->mapMetaKeysFromFormGroup( $formElement ) as $deepKey => $deepValue )
					{
						$metaKeys[$formElement->getName()][ $begin . "[" . $formElement->getName() . "]" . $deepKey . "" ] = "";
					}
				}
				else
				{
					$name = $formElement->getName();
					if ( !empty($name) )
					{
						$metaKeys[ $begin . "[$name]" ] = "";
					}
				}
			}

			return $metaKeys;
		}

		/**
		 * @param array $formGroup
		 */
		private function mapMetaKeysFromArray( $array, $begin = "" )
		{
			$metaKeys = array();
			/** @var FormElement $formElement */
			foreach ( $array as $key => $value )
			{
				if ( is_array( $value ) )
				{
					foreach ( $this->mapMetaKeysFromArray( $value ) as $deepKey => $deepValue )
					{
						$metaKeys[ $begin . "[" . $key . "]" . $deepKey . "" ] = $deepValue;
					}
				}
				else
				{
					if ( !empty($key) || is_numeric( filter_var( $key, FILTER_VALIDATE_INT ) ) )
					{
						if ( !is_numeric( filter_var( $key, FILTER_VALIDATE_INT ) ) )
						{
							$metaKeys[ $begin . "[$key]" ] = $value;
						}
						else
						{
							$metaKeys[ $begin ][ ] = $value;
						}
					}
				}
			}

			return $metaKeys;
		}
	}