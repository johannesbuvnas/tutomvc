<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 07/05/15
	 * Time: 16:40
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\FilterCommand;
	use tutomvc\FormGroup;

	class GetPostMetadataFilter extends FilterCommand
	{
		const NAME = "get_post_metadata";

		static $doNotExecute;

		function __construct()
		{
			parent::__construct( self::NAME, 99, 4 );
		}

		function execute()
		{
			if ( self::$doNotExecute ) return;

			$value    = $this->getArg( 0 );
			$postID   = $this->getArg( 1 );
			$metaKey  = $this->getArg( 2 );
			$isSingle = $this->getArg( 3 );
			$postType = get_post_type( $postID );

			/** @var MetaBox $metaBox */
			foreach ( $this->getFacade()->model->getProxy( MetaBoxProxy::NAME )->getMap() as $metaBox )
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
						$value = $this->mapValues( $formElement, $postID, $valueMap );
					}
				}
			}

			return $value;
		}

		protected function mapValues( $formElement, $postID, $map )
		{
//			var_dump( get_class( $formElement ) );
			foreach ( $map as &$value )
			{
				if ( is_array( $value ) )
				{
					$value = $this->mapValues( $formElement, $postID, $value );
				}
				else if ( is_string( $value ) )
				{
					// META KEY
					$childElement = $formElement->findFormElementByElementName( $value );
//					var_dump( get_class( $childElement ) );
					// TODO: apply_filter( get_class($childElement) . "_value", $childElement, $value );
					$value = self::getDBMetaValue( $postID, $value );
				}

				if ( empty($value) ) $value = NULL;
			}

			return $map;
		}

		public static function getDBMetaValue( $postID, $metaKey, $isSingle = TRUE )
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