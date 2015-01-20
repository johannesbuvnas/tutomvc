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

		final public static function constructPrimaryMetaKey( $postMetaBoxName, $index )
		{
			return $postMetaBoxName . "[$index]";
		}

		final public static function testMetaKey( $postMetaBoxName, $metaKey )
		{
			preg_match( self::getMetaKeyTestRegex( $postMetaBoxName ), $metaKey, $matches );

			return $matches;
		}

		final public static function testMetaKeyGroupName( $postMetaBoxName, $metaKey )
		{
			preg_match( self::getMetaKeyGroupNameRegex( $postMetaBoxName ), $metaKey, $matches );

			return $matches;
		}

		final public static function filterMetaKeyGroupName( $postMetaBoxName, $metaKey )
		{
			$matches = self::testMetaKeyGroupName( $postMetaBoxName, $metaKey );

			if ( is_array( $matches ) && count( $matches ) == 2 ) return $matches[ 1 ];

			return NULL;
		}

		final public function filterMetaKey( $metaKey )
		{
			$matches = self::testMetaKey( $this->getName(), $metaKey );

			if ( count( $matches ) == 4 )
			{
				$primaryMetaKey   = $matches[ 1 ];
				$index            = intval( $matches[ 2 ] );
				$secondaryMetaKey = $matches[ 3 ];

				$secondaryMetaKeyMatches = array();
				$secondaryMetaKeyRegex   = str_replace( "[", "\[", $secondaryMetaKey );
				$secondaryMetaKeyRegex   = str_replace( "]", "\]", $secondaryMetaKeyRegex );
				$secondaryMetaKeyRegex   = "/$secondaryMetaKeyRegex/";
				foreach ( $this->getMetaKeysMap() as $key => $value )
				{
					preg_match( $secondaryMetaKeyRegex, $value, $matches );
					if ( count( $matches ) )
					{
						$secondaryMetaKeyMatches[ $value ] = $primaryMetaKey . "[$index]" . $value;
					}
				}

				if ( count( $secondaryMetaKeyMatches ) && count( $secondaryMetaKeyMatches ) > 1 )
				{
					// We have multiple matches, this is a FormGroup
					// We need to construct a empty meta value map
					$formElement = $this->getFormElementByName( self::filterMetaKeyGroupName( $this->getName(), $metaKey ) );

					return $formElement ? self::createMetaValueMap( $formElement, self::constructPrimaryMetaKey( $primaryMetaKey, $index ), 0 ) : NULL;
				}
				else if ( count( $secondaryMetaKeyMatches ) )
				{
					return $metaKey;
				}
			}

			return FALSE;
		}

		/**
		 * @param FormGroup $formGroup
		 * @param string $primaryKey
		 *
		 * @return array
		 */
		private function createMetaKeyMap( $formGroup, $primaryKey = "" )
		{
			$metaKeyMap = array();
			/** @var FormElement $formElement */
			foreach ( $formGroup->getFormElements() as $formElement )
			{
				$name = $formElement->getName();
				if ( strlen( $name ) ) $metaKeyMap[ ] = $primaryKey . "[" . $name . "]";

				if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
				{
					/** @var FormGroup $formElement */
					$metaKeyMap = array_merge( $metaKeyMap, $this->createMetaKeyMap( $formElement, $primaryKey . "[" . $formElement->getName() . "]" ) );
				}
			}

			return $metaKeyMap;
		}

		/**
		 * @param FormElement $formElement
		 */
		final public static function createMetaValueMap( $formElement, $primaryKey = "", $level = 1 )
		{
			$metaValueMap = array();
			if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
			{
				$subMetaValueMap = array();
				/** @var FormGroup $formElement */
				/** @var FormElement $subFormElement */
				foreach ( $formElement->getFormElements() as $subFormElement )
				{
					$subMetaValueMap = array_merge( $subMetaValueMap, self::createMetaValueMap( $subFormElement, $primaryKey . "[" . $subFormElement->getName() . "]", $level + 1 ) );
				}
				if ( $level == 0 ) $metaValueMap = $subMetaValueMap;
				else $metaValueMap[ $formElement->getName() ] = $subMetaValueMap;
			}
			else
			{
				$name = $formElement->getName();
				if ( strlen( $name ) ) $metaValueMap[ $name ] = $primaryKey;
			}

			return $metaValueMap;
		}

		/* SET AND GET */
		final public static function getMetaKeyTestRegex( $postMetaBoxName )
		{
			return "/(" . $postMetaBoxName . ")\[([0-9]+)\](.*)/ix";
		}

		final public static function getMetaKeyGroupNameRegex( $postMetaBoxName )
		{
			return "/" . $postMetaBoxName . "\[[0-9]+\]\[(.*)\]/ix";
		}

		public function getValueAsFlatMetaKeyMap()
		{
			$metaKeyMap = array();
			foreach ( $this->getValue() as $key => $value )
			{
				$metaKeyMap[ ] = $this->getValueAsFlatMetaKeyMapAt( $key );
			}

			return $metaKeyMap;
		}

		public function getValueAsFlatMetaKeyMapAt( $index = 0 )
		{
			if ( $this->getValueAt( $index ) )
			{
				FormGroup::setValue( $this->getValueAt( $index ) );

				return $this->createMetaKeyMap( $this, $this->getName() . "[$index]" );
			}

			return NULL;
		}

		/**
		 * @return mixed
		 */
		public function getMetaKeysMap()
		{
			if ( !is_array( $this->_metaKeysMap ) )
			{
				$this->_metaKeysMap = $this->createMetaKeyMap( $this );
			}

			return $this->_metaKeysMap;
		}

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
	}