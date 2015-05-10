<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 09/05/15
	 * Time: 08:21
	 */

	namespace tutomvc\wp;

	use tutomvc\FormElement;

	class PostMetaUtil
	{
		/**
		 * @param FormElement $formElement
		 *
		 * @return string
		 */
		public static function constructValueFilterName( $formElement )
		{
			return get_class( $formElement ) . "_value";
		}

		public static function getPostMetaFromDB( $postID, $metaKey, $isSingle = TRUE )
		{
			if ( !intval( $postID ) ) return FALSE;

			global $wpdb;

			$query = "
				SELECT {$wpdb->postmeta}.meta_value
				FROM {$wpdb->postmeta}
				WHERE {$wpdb->postmeta}.post_id = '{$postID}'
				AND {$wpdb->postmeta}.meta_key = '{$metaKey}'
			";

			$myrows = $wpdb->get_results( $query );
			$dp     = array();
			foreach ( $myrows as $row )
			{
				if ( $isSingle ) return maybe_unserialize( $row->meta_value );
				$dp[ ] = maybe_unserialize( $row->meta_value );
			}

			return $dp;
		}
	}