<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/04/15
	 * Time: 13:40
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\core\form\groups\FormGroup;
	use tutomvc\wp\core\model\proxy\Proxy;

	class MetaBoxProxy extends Proxy
	{
		const NAME = __CLASS__;

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		/**
		 * @param int|string $postID
		 * @param string $metaKey
		 * @param bool $suppressFilters
		 *
		 * @return array|mixed|null|void
		 * @throws \ErrorException
		 */
		public function getPostMeta( $postID, $metaKey, $suppressFilters = FALSE )
		{
			$value    = NULL;
			$postType = get_post_type( $postID );

			/** @var MetaBox $metaBox */
			foreach ( $this->getMap() as $metaBox )
			{
				$valueMap = NULL;
				if ( in_array( $postType, $metaBox->getPostTypes() ) )
				{
					if ( $metaKey == $metaBox->getName() )
					{
						$int = $metaBox->countFissions( $postID );
						$metaBox->setValue( $int );
						$formElement = $metaBox;
						$valueMap    = $metaBox->getValueMapAt();
					}
					else if ( $formElement = $metaBox->findFormElementByElementName( $metaKey ) )
					{
						if ( is_a( $formElement, "\\tutomvc\\FormGroup" ) )
						{
							/** @var FormGroup $formElement */
							$valueMap = $formElement->getValueMapAt();
						}
						else
						{
							$valueMap = array($formElement->getElementName());
						}
					}

					if ( isset($valueMap) && !empty($valueMap) )
					{
						$value = $this->mapPostMeta( $valueMap, $postID, $formElement, $suppressFilters );
						if ( !$suppressFilters ) $value = MetaBoxModule::apply_filters( $value, $formElement );
					}
				}
			}

			return $value;
		}

		/**
		 * @param array $valueMap
		 * @param int|string $postID
		 * @param FormGroup $formElement
		 * @param bool $suppressFilters
		 *
		 * @return null|array
		 */
		public function mapPostMeta( $valueMap, $postID, $formElement, $suppressFilters )
		{
			foreach ( $valueMap as $key => &$value )
			{
				if ( is_array( $value ) )
				{
					$childElement = $formElement->findFormElementByName( $key );
					$value        = $this->mapPostMeta( $value, $postID, $formElement, $suppressFilters );
				}
				else if ( is_string( $value ) )
				{
					// META KEY
					$childElement = $formElement->findFormElementByElementName( $value );
					$value        = $this->getPostMetaFromDB( $postID, $value );
				}

				if ( !is_null( $childElement ) )
				{
					if ( !$suppressFilters ) $value = MetaBoxModule::apply_filters( $value, $childElement );
				}

				if ( empty($value) ) $value = NULL;
			}

			return $valueMap;
		}

		public function getPostMetaFromDB( $postID, $metaKey, $isSingle = TRUE )
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
				$dp[] = maybe_unserialize( $row->meta_value );
			}

			return $dp;
		}
	}