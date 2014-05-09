<?php
namespace tutomvc;

class PostType extends ValueObject implements IPostType
{
	/* VARS */
	private $_arguments;
	private $_columnsMap = array();
	private $_orderBy = "date";
	private $_order = "DESC";


	function __construct( $name, $arguments = NULL )
	{
		$this->setName( $name );

		if(is_array($arguments))
		{
			$this->setArguments( $arguments );
		}
		else
		{
			$labels = array(
			   'name'               => 'Custom Post Types',
			   'singular_name'      => 'Custom Post Type',
			   'add_new'            => 'Add New',
			   'add_new_item'       => 'Add New',
			   'edit_item'          => 'Edit',
			   'new_item'           => 'New',
			   'all_items'          => 'All',
			   'view_item'          => 'View',
			   'search_items'       => 'Search',
			   'not_found'          => 'No found',
			   'not_found_in_trash' => 'No found in Trash',
			   'parent_item_colon'  => '',
			   'menu_name'          => 'Custom Post Type'
			 );

			 $this->setArguments(array(
			   'labels'             => $labels,
			   'public'             => true,
			   'publicly_queryable' => true,
			   'show_ui'            => true,
			   'show_in_menu'       => true,
			   'query_var'          => true,
			   'rewrite'            => array( 'slug' => $this->getName() ),
			   'capability_type'    => 'post',
			   'has_archive'        => true,
			   'hierarchical'       => false,
			   'menu_position'      => null,
			   'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
			 ));
		}
	}

	/* METHODS */
	public function addColumn( WPAdminPostTypeColumn $column )
	{
		$this->_columnsMap[ $column->getName() ] = $column;

		return $this;
	}

	public function setArgument( $name, $value )
	{
		$this->_arguments[ $name ] = $value;

		return $this;
	}
	public function getArgument( $name )
	{
		return $this->_arguments[ $name ];
	}

	public function setArguments($arguments)
	{
		$this->_arguments = $arguments;
	}
	public function getArguments()
	{
		return $this->_arguments;
	}

	/* EVENTS */
	final public function _pre_get_posts( $wpQuery )
	{
		if( $wpQuery->get( "orderby" ) )
		{
			foreach( $this->_columnsMap as $wpAdminPostTypeColumn )
			{
				if($wpAdminPostTypeColumn->getSortable())
				{
					$wpAdminPostTypeColumn->sort( $wpQuery );
				}
			}
		}

		return $this->pre_get_posts( $wpQuery );
	}

	public function pre_get_posts( $wpQuery )
	{
		return $wpQuery;
	}

	public function manage_columns( $columns )
	{
		foreach($this->_columnsMap as $wpAdminPostTypeColumn)
		{
			$columns[ $wpAdminPostTypeColumn->getName() ] = $wpAdminPostTypeColumn->getValue();
		}

		return $columns;
	}

	public function manage_sortable_columns( $columns )
	{
		foreach($this->_columnsMap as $wpAdminPostTypeColumn)
		{
			if($wpAdminPostTypeColumn->getSortable()) $columns[ $wpAdminPostTypeColumn->getName() ] = $wpAdminPostTypeColumn->getName();
		}

		return $columns;
	}

	final public function custom_column( $columnName, $postID )
	{
		if(array_key_exists($columnName, $this->_columnsMap))
		{
			$this->_columnsMap[ $columnName ]->render( $postID );
		}
	}
}

interface IPostType
{
	/* METHODS */
	// public function hasMetaBox( $metaName );

	/* SET AND GET */
	public function setArguments($arguments);
	public function getArguments();
}
