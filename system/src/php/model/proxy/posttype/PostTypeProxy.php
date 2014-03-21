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
		register_post_type( $item->getName(), $item->getArguments() );

		return parent::add( $item, $item->getName() );
	}
}