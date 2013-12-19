<?php
namespace tutomvc;

class MenuProxy extends Proxy
{
	public function onRegister()
	{
	}

	/* ACTIONS */
	public function add( Menu $item, $key = NULL )
	{
		$this->register( $item );

		parent::add( $item, $item->getName() );
	}

	protected function register( Menu $item )
	{
		register_nav_menu( $item->getName(), $item->getDescription() );
	}
}