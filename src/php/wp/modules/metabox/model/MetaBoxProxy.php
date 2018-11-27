<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/04/15
	 * Time: 13:40
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\core\form\FormElement;
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
		 * @param null $defaultValue
		 *
		 * @return array|mixed|null|void
		 * @throws \ErrorException
		 */
		public function getPostMetaByMetaKey( $postID, $metaKey, $suppressFilters = FALSE, $defaultValue = NULL )
		{
			$value    = $defaultValue;
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
						$metaBox->setFissions( $int );
						$formElement = $metaBox;
						$valueMap    = $metaBox->getFissionKeyMap();
					}
					else if ( $formElement = $metaBox->findByElementName( $metaKey ) )
					{
						if ( $formElement instanceof FormGroup )
						{
							/** @var FormGroup $formElement */
							$valueMap = $formElement->getKeyMap();
						}
						else
						{
							$valueMap = array($formElement->getElementName());
						}
					}

					if ( isset( $valueMap ) && !empty( $valueMap ) )
					{
						$value = $this->mapPostMeta( $valueMap, $postID, $formElement, $suppressFilters );
						if ( !$suppressFilters ) $value = MetaBoxModule::apply_filters( $value, $formElement, $postID );
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
					if ( $formElement instanceof FormGroup ) $childElement = $formElement->findByName( $key );
					$value = $this->mapPostMeta( $value, $postID, $formElement, $suppressFilters );
				}
				else if ( is_string( $value ) )
				{
					// META KEY
					if ( $formElement instanceof FormGroup )
					{
						$childElement = $formElement->findByElementName( $value );
					}
					else if ( $formElement->getElementName() == $value )
					{
						$childElement = $formElement;
					}
					$value = $this->getPostMetaFromDB( $postID, $value );
				}

				if ( isset( $childElement ) && !is_null( $childElement ) )
				{
					if ( !$suppressFilters ) $value = MetaBoxModule::apply_filters( $value, $childElement, $postID );
				}

				if ( empty( $value ) ) $value = NULL;
			}

			return $valueMap;
		}

		/**
		 * Straight up SQL query to avoid native WP methods that uses hooks.
		 *
		 * @param $postID
		 * @param $metaKey
		 *
		 * @return array|bool|mixed
		 */
		public function getPostMetaFromDB( $postID, $metaKey )
		{
			if ( !intval( $postID ) ) return FALSE;

			$isSingle = substr( $metaKey, strlen( $metaKey ) - 2, 2 ) == "[]" ? FALSE : TRUE;

			global $wpdb;

			$query = "
				SELECT {$wpdb->postmeta}.meta_value
				FROM {$wpdb->postmeta}
				WHERE {$wpdb->postmeta}.post_id = '{$postID}'
				AND {$wpdb->postmeta}.meta_key = '{$metaKey}'
			";

			$myrows = $wpdb->get_results( $query );
			$dp     = array();
			$i      = 0;
			foreach ( $myrows as $row )
			{
				$i ++;
				if ( $i == 1 && ($isSingle || is_serialized( $row->meta_value )) ) return maybe_unserialize( $row->meta_value );
				$dp[] = maybe_unserialize( $row->meta_value );
			}

			return $dp;
		}

		/**
		 * @param int $postID
		 * @param MetaBox $metaBox
		 * @param FormElement|null $formElement
		 * @param int|null $fissionIndex
		 *
		 * @return mixed
		 */
		public function getPostMeta( $postID, $metaBox, $formElement = NULL, $fissionIndex = NULL )
		{
			if ( is_int( $fissionIndex ) ) $metaBox->setIndex( $fissionIndex );
			else $metaBox->setIndex( 0 );

			$postType = get_post_type( $postID );
			if ( !in_array( $postType, $metaBox->getPostTypes() ) ) return FALSE;

			if ( !is_null( $formElement ) )
			{
				if ( $formElement instanceof FormElement )
				{
					$metaKey = $formElement->getElementName();

					return get_post_meta( $postID, $metaKey, TRUE );
				}

				return FALSE;
			}

			$metaKey      = $metaBox->getName();
			$fissonsCount = $metaBox->countFissions( $postID );
			if ( empty( $fissonsCount ) ) return NULL;
			$allMetaBoxMeta = get_post_meta( $postID, $metaKey, FALSE );

			if ( !is_null( $fissionIndex ) )
			{
				if ( is_int( $fissionIndex ) )
				{
					if ( is_array( $allMetaBoxMeta ) && array_key_exists( $fissionIndex, $allMetaBoxMeta ) ) return $allMetaBoxMeta[ $fissionIndex ];

					return FALSE;
				}

				return FALSE;
			}

			return $allMetaBoxMeta;
		}

		/**
		 * @param int $postID
		 * @param string $metaBoxName
		 * @param null|string $formElementName
		 * @param null|int $fissionIndex
		 *
		 * @return bool|mixed
		 */
		public function getPostMetaByName( $postID, $metaBoxName, $formElementName = NULL, $fissionIndex = NULL )
		{
			if ( !$this->get( $metaBoxName ) ) return FALSE;
			/** @var MetaBox $metaBox */
			$metaBox     = $this->get( $metaBoxName );
			$formElement = NULL;

			if ( !is_null( $formElementName ) )
			{
				if ( is_string( $formElementName ) )
				{
					$formElement = $metaBox->findByName( $formElementName );
					if ( !($formElement instanceof FormElement) ) return FALSE;
				}
				else
				{
					return FALSE;
				}
			}

			return $this->getPostMeta( $postID, $metaBox, $formElement, $fissionIndex );
		}
	}