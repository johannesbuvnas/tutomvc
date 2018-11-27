<?php

	namespace tutomvc\wp\metabox;

	use tutomvc\core\form\groups\FormGroup;
	use tutomvc\wp\core\model\proxy\Proxy;

	class UserMetaBoxProxy extends Proxy
	{
		const NAME = __CLASS__;

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		/**
		 * @param int|string $userID
		 * @param string $metaKey
		 * @param bool $suppressFilters
		 * @param null $defaultValue
		 *
		 * @return array|mixed|null
		 * @throws \ErrorException
		 */
		public function getUserMetaByMetaKey( $userID, $metaKey, $suppressFilters = FALSE, $defaultValue = NULL )
		{
			$value = $defaultValue;

			/** @var MetaBox $metaBox */
			foreach ( $this->getMap() as $metaBox )
			{
				$valueMap = NULL;
				if ( $metaKey == $metaBox->getName() )
				{
					$int = $metaBox->countFissions( $userID );
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
					$value = $this->mapPostMeta( $valueMap, $userID, $formElement, $suppressFilters );
					if ( !$suppressFilters ) $value = MetaBoxModule::apply_filters( $value, $formElement, $userID );
				}
			}

			return $value;
		}

		/**
		 * @param array $valueMap
		 * @param int|string $userID
		 * @param FormGroup $formElement
		 * @param bool $suppressFilters
		 *
		 * @return null|array
		 */
		public function mapPostMeta( $valueMap, $userID, $formElement, $suppressFilters )
		{
			foreach ( $valueMap as $key => &$value )
			{
				if ( is_array( $value ) )
				{
					if ( $formElement instanceof FormGroup ) $childElement = $formElement->findByName( $key );
					$value = $this->mapPostMeta( $value, $userID, $formElement, $suppressFilters );
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
					$value = $this->getUserMetaFromDB( $userID, $value );
				}

				if ( isset( $childElement ) && !is_null( $childElement ) )
				{
					if ( !$suppressFilters ) $value = MetaBoxModule::apply_filters( $value, $childElement, $userID );
				}

				if ( empty( $value ) ) $value = NULL;
			}

			return $valueMap;
		}

		/**
		 * Straight up SQL query to avoid native WP methods that uses hooks.
		 *
		 * @param $userID
		 * @param $metaKey
		 *
		 * @return array|bool|mixed
		 */
		public function getUserMetaFromDB( $userID, $metaKey )
		{
			if ( !intval( $userID ) ) return FALSE;

			$isSingle = substr( $metaKey, strlen( $metaKey ) - 2, 2 ) == "[]" ? FALSE : TRUE;

			global $wpdb;

			$query = "
				SELECT {$wpdb->usermeta}.meta_value
				FROM {$wpdb->usermeta}
				WHERE {$wpdb->usermeta}.user_id = '{$userID}'
				AND {$wpdb->usermeta}.meta_key = '{$metaKey}'
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
	}