<?php
	namespace tutomvc;

	class PostType extends ValueObject implements IPostType
	{
		/* VARS */
		private $_arguments;
		private $_columnsMap = array();
		private $_orderBy    = "date";
		private $_order      = "DESC";

		function __construct( $name, $arguments = NULL )
		{
			$this->setName( $name );

			if ( is_array( $arguments ) )
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

				$this->setArguments( array(
					'labels'             => $labels,
					'public'             => TRUE,
					'publicly_queryable' => TRUE,
					'show_ui'            => TRUE,
					'show_in_menu'       => TRUE,
					'query_var'          => TRUE,
					'rewrite'            => array('slug' => $this->getName()),
					'capability_type'    => 'post',
					'has_archive'        => TRUE,
					'hierarchical'       => FALSE,
					'menu_position'      => NULL,
					'supports'           => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
				) );
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

		public function setArguments( $arguments )
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
			if ( $wpQuery->get( "orderby" ) )
			{
				foreach ( $this->_columnsMap as $wpAdminPostTypeColumn )
				{
					if ( $wpAdminPostTypeColumn->getSortable() && $wpQuery->get( "orderby" ) == $wpAdminPostTypeColumn->getName() )
					{
						$wpAdminPostTypeColumn->sort( $wpQuery );
					}
				}
			}

			return $this->pre_get_posts( $wpQuery );
		}
		/* HOOKS */
		/**
		 * This hook is fired before data is saved for this post type.
		 * Override for custom validation.
		 *
		 * @param $data Array
		 * @param $postArray The $_POST array
		 */
		public function filter_wp_insert_post_data( $data, $postArray )
		{
			return $data;
		}

		/**
		 * Fires once a post has been saved.
		 *
		 * @param int $postID Post ID.
		 * @param WP_Post $post Post object.
		 * @param bool $update Whether this is an existing post being updated or not.
		 */
		public function wp_action_wp_insert_post( $postID, $post, $update )
		{
		}

		/**
		 * Filter the default post title initially used in the "Write Post" form.
		 *
		 * @param string  title Default post title.
		 * @param WP_Post $post Post object.
		 */
		function wp_filter_default_title( $title, $post )
		{
			return $title;
		}

		/**
		 * This hook is fired when the post-page is loaded in WP Admin.
		 */
		public function action_load_post()
		{

		}

		/**
		 * Override.
		 */
		public function save_post( $postID )
		{
		}

		/**
		 * Override.
		 */
		public function post_updated( $postAfter, $postBefore )
		{
		}

		/**
		 * Override.
		 */
		public function pre_post_update( $postID )
		{
		}

		/**
		 * Override.
		 */
		public function pre_get_posts( $wpQuery )
		{
			return $wpQuery;
		}

		public function manage_columns( $columns )
		{
			foreach ( $this->_columnsMap as $wpAdminPostTypeColumn )
			{
				$columns[ $wpAdminPostTypeColumn->getName() ] = $wpAdminPostTypeColumn->getValue();
			}

			return $columns;
		}

		public function manage_sortable_columns( $columns )
		{
			foreach ( $this->_columnsMap as $wpAdminPostTypeColumn )
			{
				if ( $wpAdminPostTypeColumn->getSortable() ) $columns[ $wpAdminPostTypeColumn->getName() ] = $wpAdminPostTypeColumn->getName();
			}

			return $columns;
		}

		final public function custom_column( $columnName, $postID )
		{
			if ( array_key_exists( $columnName, $this->_columnsMap ) )
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
		public function setArguments( $arguments );

		public function getArguments();
	}
