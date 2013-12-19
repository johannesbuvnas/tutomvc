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
		$meta = array();
		
		if(!$metaKey || !strlen($metaKey))
		{
			$postType = get_post_type( $postID );
			foreach($this->getFacade()->model->getProxy( MetaBoxProxy::NAME )->getMap() as $metaBox)
			{
				if($metaBox->hasPostType( $postType ))
				{
					$meta[ $metaBox->getName() ] = $metaBox->getMetaBoxMap( $postID );
				}
			}
		}

		return $meta && count($meta) ? $meta : NULL;
	}
}