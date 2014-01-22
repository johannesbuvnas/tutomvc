<?php
namespace tutomvc;

class GetMetaDatFilter extends FilterCommand
{
	function __construct()
	{
		parent::__construct( "get_post_metadata" );
		$this->acceptedArguments = 4;
	}

	function execute( $null, $postID, $metaKey, $isSingle )
	{		
		if(!$metaKey || !strlen($metaKey))
		{
			$meta = array();
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
					$metaField = $metaBox->getFieldByMetaKey( $metaKey );
					if($metaField)
					{
						$meta = apply_filters( FilterCommand::META_VALUE, $this->getDBMetaValue( $postID, $metaKey ), $metaField );
					}
				}
			}
		}

		return isset($meta) ? $meta : NULL;
	}

	public function getDBMetaValue( $postID, $metaKey )
	{
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