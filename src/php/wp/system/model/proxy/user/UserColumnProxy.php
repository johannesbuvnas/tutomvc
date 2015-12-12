<?php
namespace tutomvc\wp;

class UserColumnProxy extends Proxy
{
	const NAME = __CLASS__;

	public function onRegister()
	{
		add_filter( "manage_users_columns", array( $this, "manage_columns" ), 1 );
		add_filter( "manage_users_sortable_columns", array( $this, "manage_sortable_columns" ), 1 );

		add_filter( "manage_users_custom_column", array( $this, "custom_column" ), 1, 3 );
		add_action( "pre_user_query", array( $this, "pre_user_query" ), 1, 1 );
	}

	public function add( $item, $key = NULL )
	{
		return parent::add( $item, $item->getName() );
	}

	/* ACTIONS */
	public function custom_column( $content, $columnName, $objectID )
	{
		if($this->get( $columnName ))
		{
			$content = $this->get( $columnName )->getContent( $content, $objectID );
		}

		return $content;
	}

	public function pre_user_query( $wpUserQuery )
	{
		if($this->get( $wpUserQuery->get( "orderby" ) )) $this->get( $wpUserQuery->get( "orderby" ) )->sort( $wpUserQuery );
	}

	/* FILTERS */
	public function manage_columns( $columns )
	{
		foreach($this->getMap() as $column)
		{
			$columns[ $column->getName() ] = $column->getValue();
		}

		return $columns;
	}
	public function manage_sortable_columns( $columns )
	{
		foreach($this->getMap() as $column)
		{
			if($column->getSortable()) $columns[ $column->getName() ] = $column->getName();
		}

		return $columns;
	}
}