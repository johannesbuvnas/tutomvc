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

		add_filter( "manage_edit-" . $item->getName() . "_columns", array( $item, "manage_columns" ) );
		add_filter( "manage_edit-" . $item->getName() . "_sortable_columns", array( $item, "manage_sortable_columns" ) );

		add_action( "manage_" . $item->getName() . "_posts_custom_column", array( $item, "custom_column" ), 1, 2 );

		return parent::add( $item, $item->getName() );
	}
}