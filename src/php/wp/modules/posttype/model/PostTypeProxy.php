<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 04/12/15
	 * Time: 15:57
	 */

	namespace tutomvc\wp\posttype;

	use tutomvc\wp\Proxy;
	use WP_Post;

	class PostTypeProxy extends Proxy
	{
		const NAME = __CLASS__;

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function onRegister()
		{
			add_action( "init", array($this, "init") );
			add_action( "admin_init", array($this, "admin_init") );
			add_action( "post_updated", array($this, "post_updated"), 10, 3 );
			add_action( "pre_post_update", array($this, "pre_post_update"), 10, 2 );
			add_action( "wp_insert_post", array($this, "wp_insert_post"), 10, 3 );
			add_filter( "default_title", array($this, "default_title"), 10, 3 );
			add_filter( "wp_insert_post_data", array($this, "wp_insert_post_data"), 10, 2 );
			add_filter( "post_updated_messages", array($this, "post_updated_messages"), 10, 1 );
		}

		/**
		 * @param $key
		 *
		 * @return PostType|null
		 */
		public function get( $key )
		{
			return parent::get( $key );
		}

		/* HOOKS */
		public function init()
		{
			/** @var PostType $postType */
			foreach ( $this->getMap() as $postType )
			{
				register_post_type( $postType->getName(), $postType->getArgs() );

				// Actions
				add_action( "save_post_" . $postType->getName(), array($postType, "action_save"), 1, 3 );
				add_action( "manage_" . $postType->getName() . "_posts_custom_column", array(
					$postType,
					"action_custom_column"
				), 1, 2 );
				// Filters
				add_filter( "manage_" . $postType->getName() . "_posts_columns", array(
					$postType,
					"filter_manage_columns"
				), 1, 1 );
				add_filter( "manage_" . $postType->getName() . "_sortable_columns", array(
					$postType,
					"filter_manage_sortable_columns"
				), 1, 1 );
			}
		}

		public function admin_init()
		{
			add_action( "current_screen", array($this, "current_screen") );
		}

		public function current_screen()
		{
			/** @var \WP_Screen $screen */
			$screen = get_current_screen();
			if ( !empty($screen->post_type) )
			{
				if ( $this->has( $screen->post_type ) )
				{
					$this->get( $screen->post_type )->action_load_admin_page();
				}
			}
		}

		public function post_updated( $postID, $postAfter, $postBefore )
		{
			$postType = get_post_type( $postID );
			if ( $this->has( $postType ) )
			{
				$this->get( $postType )->action_updated( $postID, $postAfter, $postBefore );
			}
		}

		public function pre_post_update( $postID, $data )
		{
			$postType = get_post_type( $postID );
			if ( $this->has( $postType ) )
			{
				$this->get( $postType )->action_pre_update( $postID, $data );
			}
		}

		public function wp_insert_post( $postID, $post, $update )
		{
			$postType = get_post_type( $postID );
			if ( $this->has( $postType ) )
			{
				$this->get( $postType )->action_insert( $postID, $post, $update );
			}
		}

		/**
		 * @param string $post_title
		 * @param WP_Post $post
		 *
		 * @return string
		 */
		public function default_title( $post_title, $post )
		{
			if ( $this->has( $post->post_type ) )
			{
				return $this->get( $post->post_type )->filter_default_title( $post_title, $post );
			}

			return $post_title;
		}

		/**
		 * @param array $data
		 * @param array $postarr
		 *
		 * @return array
		 */
		public function wp_insert_post_data( $data, $postarr )
		{
			if ( $this->has( $data[ 'post_type' ] ) )
			{
				return $this->get( $data[ 'post_type' ] )->filter_insert_post_data( $data, $postarr );
			}

			return $data;
		}

		public function post_updated_messages( $messages )
		{
			/** @var PostType $postType */
			foreach ( $this->getMap() as $postType )
			{
				$messages = $postType->getMessages();
				if ( is_array( $messages ) )
				{
					$messages[ $postType->getName() ] = $messages;
				}
			}

			return $messages;
		}
	}