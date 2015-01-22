<?php
namespace tutomvc;

class GetMetaDatFilter extends FilterCommand
{

	private static $_skip = FALSE;

	function __construct()
	{
		parent::__construct( "get_post_metadata" );
		$this->acceptedArguments = 4;
		$this->priority = 99;
	}

	function execute()
	{		
		if( self::$_skip ) return;

		$postID = $this->getArg(1);
		$metaKey = $this->getArg(2);
		$isSingle = $this->getArg(3);
		if(!$metaKey || !strlen($metaKey))
		{
			// Retrieve default meta
			self::$_skip = TRUE;
			$meta = get_metadata( "post", $postID, $metaKey, $isSingle );
			self::$_skip = FALSE;
			
			$postType = get_post_type( $postID );
			foreach($this->getFacade()->model->getProxy( MetaBoxProxy::NAME )->getMap() as $metaBox)
			{
				if($metaBox->hasPostType( $postType ))
				{
					$meta[ $metaBox->getName() ] = array_map( array($this, "convertMetaVOToValue"), $metaBox->getMetaBoxMap( $postID ) );
				}
			}
		}
		else
		{
			$postType = get_post_type( $postID );

			foreach($this->getFacade()->model->getProxy( MetaBoxProxy::NAME )->getMap() as $metaBox)
			{
				if($metaBox->hasPostType( $postType ))
				{
					if($metaBox->getName() == $metaKey)
					{
						$meta = array_map( array($this, "convertMetaVOToValue"), $metaBox->getMetaBoxMap( $postID ) );
					}
					else if($metaField = $metaBox->getFieldByMetaKey( $metaKey ))
					{
						// Default filter command by Tuto MVC
						$meta = apply_filters( FilterCommand::META_VALUE, $postID, self::getDBMetaValue( $postID, $metaKey ), $metaField );
						// Possibility for other listeners to filter the meta value
						$meta = apply_filters( FilterCommand::constructFilterMetaValueCommandName( $metaBox->getName(), $metaField->getName() ), $meta, $postID );
					}
				}
			}
		}

		return isset($meta) ? $meta : NULL;
	}

	public static function getDBMetaValue( $postID, $metaKey )
	{
		if( !intval($postID) ) return FALSE;

		global $wpdb;

		$query = "
			SELECT {$wpdb->postmeta}.meta_value
			FROM {$wpdb->postmeta}
			WHERE {$wpdb->postmeta}.post_id = '{$postID}'
			AND {$wpdb->postmeta}.meta_key = '{$metaKey}'
		";

		$myrows = $wpdb->get_results( $query );
		$dp = array();
		foreach($myrows as $row) $dp[] = $row->meta_value;
		
		return $dp;
	}

	public static function getDBUserMetaValue( $userID, $metaKey )
	{
		if( !intval($userID) ) return FALSE;

		global $wpdb;

		$query = "
			SELECT {$wpdb->usermeta}.meta_value
			FROM {$wpdb->usermeta}
			WHERE {$wpdb->usermeta}.user_id = '{$userID}'
			AND {$wpdb->usermeta}.meta_key = '{$metaKey}'
		";

		$myrows = $wpdb->get_results( $query );
		$dp = array();
		foreach($myrows as $row) $dp[] = $row->meta_value;
		
		return $dp;
	}

	public function convertMetaVOToValue( $metaVO )
	{
		if(is_a($metaVO, "tutomvc\MetaVO"))
		{
			return $metaVO->getValue();
		}
		else if(is_array($metaVO))
		{
			return array_map( array($this, "convertMetaVOToValue"), $metaVO );
		}
		else
		{
			return $metaVO;
		}
	}
}