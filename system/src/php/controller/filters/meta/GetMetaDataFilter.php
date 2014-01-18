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
					$meta[ $metaBox->getName() ] = array_map( array($this, "convertMetaVOToValue"), $metaBox->getMetaBoxMap( $postID ) );
				}
			}
		}

		return $meta && count($meta) ? $meta : NULL;
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