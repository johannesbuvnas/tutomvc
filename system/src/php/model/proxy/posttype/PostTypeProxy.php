<?php
namespace tutomvc;

class PostTypeProxy extends Proxy
{
	const NAME = __CLASS__;

	public function add( PostType $item )
	{
		if( $this->getFacade()->model->hasProxy( MetaBoxProxy::NAME ) )
		{
			foreach( $this->getFacade()->model->getProxy( MetaBoxProxy::NAME )->getMap() as $metaBox )
			{
				if($metaBox->hasPostType( $item->getName() )) $item->addMetaBox( $metaBox );
			}
		}

		register_post_type( $item->getName(), $item->getArguments() );

		parent::add( $item, $item->getName() );
	}
}