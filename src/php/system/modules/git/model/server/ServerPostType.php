<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 26/11/14
	 * Time: 09:29
	 */

	namespace tutomvc\modules\git;

	use tutomvc\Notification;
	use tutomvc\PostType;
	use tutomvc\TutoMVC;

	class ServerPostType extends PostType
	{
		const NAME = "server";

		function __construct()
		{
			$labels = array(
				'name'          => __( "Server", TutoMVC::NAME ),
				'singular_name' => __( "Server", TutoMVC::NAME ),
				'menu_name'     => __( "Server", TutoMVC::NAME ),
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

			$this->addColumn( new StatusColumn() );
		}

		/* HOOKS */
		public function filter_post_updated_messages_error_on_publish( $messages )
		{
			$messages[ self::NAME ][ 6 ] = __( "Settings saved with errors.", TutoMVC::NAME );
			do_action( Notification::ACTION_ADD_NOTIFICATION, __( "Couldn't connect to server, try edit settings and update.", TutoMVC::NAME ), Notification::TYPE_ERROR );

			return $messages;
		}

		public function action_load_post()
		{
			if ( isset($_GET[ 'post' ]) )
			{
				if ( get_post_meta( $_GET[ 'post' ], GitModule::POST_META_STATUS, TRUE ) == GitModule::POST_META_STATUS_VALUE_ERROR )
				{
					add_filter( "post_updated_messages", array(
						$this,
						"filter_post_updated_messages_error_on_publish"
					) );
				}
			}
		}

		public function wp_action_wp_insert_post( $postID, $post, $update )
		{
			$address  = get_post_meta( $postID, ServerMetaBox::constructMetaKey( ServerMetaBox::NAME, ServerMetaBox::ADDRESS ), TRUE );
			$port     = get_post_meta( $postID, ServerMetaBox::constructMetaKey( ServerMetaBox::NAME, ServerMetaBox::PORT ), TRUE );
			$username = get_post_meta( $postID, ServerMetaBox::constructMetaKey( ServerMetaBox::NAME, ServerMetaBox::USERNAME ), TRUE );
			$password = get_post_meta( $postID, ServerMetaBox::constructMetaKey( ServerMetaBox::NAME, ServerMetaBox::PASSWORD ), TRUE );
			if ( !apply_filters( ServerProxy::FILTER_TEST, $address, $port, $username, $password ) )
			{
				update_post_meta( $postID, GitModule::POST_META_STATUS, GitModule::POST_META_STATUS_VALUE_ERROR );
			}
			else
			{
				update_post_meta( $postID, GitModule::POST_META_STATUS, GitModule::POST_META_STATUS_VALUE_OK );
			}

		}
	}