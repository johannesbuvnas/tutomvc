<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 25/11/14
	 * Time: 09:13
	 */

	namespace tutomvc\modules\git;

	use tutomvc\PostType;
	use tutomvc\TutoMVC;

	class GitKeyPostType extends PostType
	{
		const NAME = "git-key";

		function __construct()
		{
			$labels = array(
				'name'          => __( "Git Key", TutoMVC::NAME ),
				'singular_name' => __( "Git Key", TutoMVC::NAME ),
				'menu_name'     => __( "Git Key", TutoMVC::NAME ),
			);

			if ( !is_multisite() )
			{
				$caps = array(
					"edit_post"     => "manage_options",
					"read_post"     => "manage_options",
					"delete_post"   => "manage_options",
					"publish_posts" => "manage_options"
				);
			}
			else
			{
				$caps = array(
					"edit_post"     => "manage_network_options",
					"read_post"     => "manage_network_options",
					"delete_post"   => "manage_network_options",
					"publish_posts" => "manage_network_options"
				);
			}

			$args = array(
				'labels'             => $labels,
				'public'             => FALSE,
				'publicly_queryable' => FALSE,
				'show_ui'            => TRUE,
				'show_in_menu'       => TRUE,
				'query_var'          => TRUE,
				'capability_type'    => 'post',
				'has_archive'        => FALSE,
				'hierarchical'       => FALSE,
				'menu_position'      => 100,
				'capabilities'       => $caps,
				'supports'           => array(
					'title'
				)
			);

			parent::__construct( self::NAME, $args );

			add_filter( "default_title", array($this, "filter_default_title"), 99, 2 );
			add_action( "deleted_post", array($this, "deleted_post"), 0, 1 );
		}

		public function filter_default_title( $title, $objectID )
		{
			if ( get_post_type( $objectID ) == self::NAME )
			{
				global $current_user;
				$domain = parse_url( site_url(), PHP_URL_HOST );
				$title  = sanitize_email( $current_user->data->user_login . "@" . $domain );
			}

			return $title;
		}

		public function deleted_post( $objectID )
		{
			if ( get_post_type( $objectID ) == self::NAME )
			{
				do_action( GitKeyProxy::ACTION_DELETE, $objectID );
			}
		}

		public function wp_action_wp_insert_post( $postID, $post, $update )
		{
			if ( is_null( apply_filters( GitKeyProxy::FILTER_CAT_PRIVATE_KEY, $postID ) ) )
			{
				do_action( GitKeyProxy::ACTION_ADD, $postID );
			}
		}
	}