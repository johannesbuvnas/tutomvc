<?php
namespace tutomvc\wp;

class GetUserMetaDataFilter extends FilterCommand
{
	private static $_skip = FALSE;

	function __construct()
	{
		parent::__construct( "get_user_metadata" );
		$this->acceptedArguments = 4;
	}

	function execute()
	{		
		if(self::$_skip) return;

		$userID = $this->getArg(1);
		$metaKey = $this->getArg(2);
		$isSingle = $this->getArg(3);
		if(!$metaKey || !strlen($metaKey))
		{
			// Retrieve default meta
			self::$_skip = TRUE;
			$meta = get_metadata( "user", $userID, $metaKey, $isSingle );
			self::$_skip = FALSE;

			foreach($this->getFacade()->model->getProxy( UserMetaProxy::NAME )->getMap() as $metaBox)
			{
				$meta[ $metaBox->getName() ] = array_map( array($this, "convertMetaVOToValue"), $metaBox->getMetaBoxMap( $userID ) );
			}
			if(!count($meta)) $meta = NULL;
		}
		else
		{
			foreach($this->getFacade()->model->getProxy( UserMetaProxy::NAME )->getMap() as $metaBox)
			{
				if($metaBox->getName() == $metaKey)
				{
					$meta = array_map( array($this, "convertMetaVOToValue"), $metaBox->getMetaBoxMap( $userID ) );
				}
				else if($metaField = $metaBox->getFieldByMetaKey( $metaKey ))
				{
					// Default filter command by Tuto MVC
					$meta = apply_filters( FilterCommand::META_VALUE, $userID, self::getDBMetaValue( $userID, $metaKey ), $metaField );
					// Possibility for other listeners to filter the meta value
					$meta = apply_filters( FilterCommand::constructFilterMetaValueCommandName( $metaBox->getName(), $metaField->getName() ), $meta, $userID );
				}
			}
		}

		return isset($meta) ? $meta : NULL;
	}

	public static function getDBMetaValue( $userID, $metaKey )
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