<?php
namespace tutomvc;

class PostType extends ValueObject implements IPostType
{
	/* VARS */
	private $_arguments;
	private $_fieldsMap = array();
	private $_metaMap = array();
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
	public function setOrderBy( $value )
	{
		$this->_orderBy = $value;

		return $this;
	}
	public function getOrderBy()
	{
		return $this->_orderBy;
	}

	public function setOrder( $value )
	{
		$this->_order = $value;

		return $this;
	}
	public function getOrder()
	{
		return $this->_order;
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
}

interface IPostType
{
	/* METHODS */
	// public function hasMetaBox( $metaName );
	
	/* SET AND GET */
	public function setArguments($arguments);
	public function getArguments();
}