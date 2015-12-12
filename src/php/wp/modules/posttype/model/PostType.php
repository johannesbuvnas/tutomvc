<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 04/12/15
	 * Time: 15:40
	 */

	namespace tutomvc\wp\posttype;

	use tutomvc\NameObject;
	use tutomvc\WPAdminColumn;
	use WP_Post;

	class PostType extends NameObject
	{
		protected $_args;
		protected $_columnsMap = array();
		protected $_messages;

		function __construct( $name, $arguments = NULL )
		{
			parent::__construct( $name );

			if ( is_array( $arguments ) )
			{
				$this->setArgs( $arguments );
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

				$this->setArgs( array(
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

		/* STATIC MTHODS */
		public static function getPublicSlug( $postTypeName )
		{
			$object = get_post_type_object( $postTypeName );

			if ( is_object( $object ) && isset($object->public) && $object->public )
			{
				if ( is_array( $object->rewrite ) )
				{
					return $object->rewrite[ 'slug' ];
				}

				return $postTypeName;
			}

			return NULL;
		}

		/* METHODS */
		public function addColumn( WPAdminColumn $column )
		{
			$this->_columnsMap[ $column->getName() ] = $column;

			return $this;
		}

		public function setArg( $name, $value )
		{
			$this->_args[ $name ] = $value;

			return $this;
		}

		public function getArg( $name )
		{
			return $this->_args[ $name ];
		}

		public function setArgs( $arguments )
		{
			$this->_args = $arguments;
		}

		public function getArgs()
		{
			return $this->_args;
		}

		/**
		 * @return array|null
		 */
		public function getMessages()
		{
			return $this->_messages;
		}

		/**
		 * @param array $messages
		 */
		public function setMessages( $messages )
		{
			$this->_messages = $messages;
		}

		/* HOOKS */
		/**
		 * This hook is fired before data is saved for this post type.
		 * Override for custom validation.
		 *
		 * @param array $data
		 * @param array $postArray The $_POST array
		 *
		 * @return array
		 */
		public function filter_insert_post_data( $data, $postArray )
		{
			return $data;
		}

		/**
		 * Filter the default post title initially used in the "Write Post" form.
		 *
		 * @param string $title Default post title.
		 * @param WP_Post $post Post object.
		 *
		 * @return string
		 */
		function filter_default_title( $title, $post )
		{
			return $title;
		}

		function filter_messages( $messages )
		{
			return $messages;
		}

		/**
		 * Fires once a post has been saved.
		 *
		 * @param int $postID Post ID.
		 * @param WP_Post $post Post object.
		 * @param bool $update Whether this is an existing post being updated or not.
		 */
		public function action_insert( $postID, $post, $update )
		{
		}

		public function action_load_admin_page()
		{
		}

		public function action_save( $postID, $post, $update )
		{
		}

		public function action_updated( $postID, $postAfter, $postBefore )
		{
		}

		public function action_pre_update( $postID, $data )
		{
		}

		public function filter_manage_columns( $columns )
		{
			/** @var WPAdminColumn $wpAdminColumn */
			foreach ( $this->_columnsMap as $wpAdminColumn )
			{
				$columns[ $wpAdminColumn->getName() ] = $wpAdminColumn->getTitle();
			}

			return $columns;
		}

		public function filter_manage_sortable_columns( $columns )
		{
			/** @var WPAdminColumn $wpAdminColumn */
			foreach ( $this->_columnsMap as $wpAdminColumn )
			{
				if ( $wpAdminColumn->isSortable() ) $columns[ $wpAdminColumn->getName() ] = $wpAdminColumn->getName();
			}

			return $columns;
		}

		public function action_custom_column( $columnName, $postID )
		{
			if ( array_key_exists( $columnName, $this->_columnsMap ) )
			{
				/** @var WPAdminColumn $wpAdminColumn */
				$wpAdminColumn = $this->_columnsMap[ $columnName ];
				$wpAdminColumn->render( $postID );
			}
		}
	}