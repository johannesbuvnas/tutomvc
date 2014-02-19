<?php
namespace tutomvc;

class PostTypeProxy extends Proxy
{
	const NAME = __CLASS__;

	public function onRegister()
	{
		$this->getFacade()->controller->registerCommand( new PreGetPostsCommand() );
	}

	public function add( $item, $key = NULL )
	{
		// Wtf is this?
		// if( $this->getFacade()->model->hasProxy( MetaBoxProxy::NAME ) )
		// {
		// 	foreach( $this->getFacade()->model->getProxy( MetaBoxProxy::NAME )->getMap() as $metaBox )
		// 	{
		// 		if($metaBox->hasPostType( $item->getName() )) $item->addMetaBox( $metaBox );
		// 	}
		// }

		register_post_type( $item->getName(), $item->getArguments() );

		return parent::add( $item, $item->getName() );
	}
}