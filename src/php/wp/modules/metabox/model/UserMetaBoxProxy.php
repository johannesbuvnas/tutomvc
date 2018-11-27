<?php

	namespace tutomvc\wp\metabox;

	use tutomvc\core\form\FormElement;
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

		/**
		 * @param $userID
		 * @param UserMetaBox $metaBox
		 * @param FormElement|null $formElement
		 * @param int|null $fissionIndex
		 *
		 * @return mixed
		 */
		public function getUserMeta( $userID, $metaBox, $formElement = NULL, $fissionIndex = NULL )
		{
			if ( is_int( $fissionIndex ) ) $metaBox->setIndex( $fissionIndex );
			else $metaBox->setIndex( 0 );

			if ( !is_null( $formElement ) )
			{
				if ( $formElement instanceof FormElement )
				{
					$metaKey = $formElement->getElementName();

					return get_user_meta( $userID, $metaKey, TRUE );
				}

				return FALSE;
			}

			$metaKey      = $metaBox->getName();
			$fissonsCount = $metaBox->countFissions( $userID );
			if ( empty( $fissonsCount ) ) return NULL;
			$allMetaBoxMeta = get_user_meta( $userID, $metaKey, FALSE );

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
		 * @param $userID
		 * @param string $metaBoxName
		 * @param null|string $formElementName
		 * @param null|int $fissionIndex
		 *
		 * @return bool|mixed
		 */
		public function getUserMetaByName( $userID, $metaBoxName, $formElementName = NULL, $fissionIndex = NULL )
		{
			if ( !$this->get( $metaBoxName ) ) return FALSE;
			/** @var UserMetaBox $metaBox */
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

			return $this->getUserMeta( $userID, $metaBox, $formElement, $fissionIndex );
		}
	}