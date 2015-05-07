<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 19/01/15
	 * Time: 09:30
	 */

	namespace tutomvc;

	class PostMetaBox extends FissileFormGroup
	{
		private $_metaKeysMap;

		/* SET AND GET */
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