<?php
namespace tutomvc;

class UserMetaProxy extends Proxy
{
	const NAME = __CLASS__;

	public function onRegister()
	{
		/* ACTIONS */
		$this->getFacade()->controller->registerCommand( new RenderUserMetaBoxCommand() );
		$this->getFacade()->controller->registerCommand( new ProfileUpdateActionCommand() );
		/* FILTERS */
		$this->getFacade()->controller->registerCommand( new GetUserMetaDataFilter() );
	}

	public function add( $item, $key = NULL )
	{
		parent::add( $item, $item->getName() );
	}
}