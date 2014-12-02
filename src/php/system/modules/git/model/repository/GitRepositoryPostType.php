<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 25/11/14
	 * Time: 09:13
	 */

	namespace tutomvc\modules\git;

	use tutomvc\Notification;
	use tutomvc\PostType;
	use tutomvc\TutoMVC;

	class GitRepositoryPostType extends PostType
	{
		const NAME = "git-repository";

		function __construct()
		{
			$labels = array(
				'name'          => __( "Git Repository", TutoMVC::NAME ),
				'singular_name' => __( "Git Repository", TutoMVC::NAME ),
				'menu_name'     => __( "Git Repository", TutoMVC::NAME ),
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
			$messages[ self::NAME ][ 6 ] = $messages[ self::NAME ][ 1 ] = __( "Settings saved with errors.", TutoMVC::NAME );
			do_action( Notification::ACTION_ADD_NOTIFICATION, __( "Couldn't connect to git repository, try edit settings and update.", TutoMVC::NAME ), Notification::TYPE_ERROR );

			return $messages;
		}

		public function action_load_post()
		{
			if ( isset($_GET[ 'post' ]) )
			{
				if ( get_post_meta( $_GET[ 'post' ], StatusMetaField::NAME, TRUE ) == StatusMetaField::ERROR )
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
			$repositoryPath          = apply_filters( GitRepositoryProxy::FILTER_LOCATE_REPOSITORY, $postID );
			$gitSSH                  = get_post_meta( $postID, GitRepositoryMetaBox::constructMetaKey( GitRepositoryMetaBox::NAME, GitRepositoryMetaBox::ADDRESS ), TRUE );
			$branch                  = get_post_meta( $postID, GitRepositoryMetaBox::constructMetaKey( GitRepositoryMetaBox::NAME, GitRepositoryMetaBox::BRANCH ), TRUE );
			$keyObjectID             = intval( get_post_meta( $postID, GitRepositoryMetaBox::constructMetaKey( GitRepositoryMetaBox::NAME, GitKeyPostType::NAME ), TRUE ) );
			$sshPrivateKey           = GitKeyProxy::locatePrivateSSHKey( $keyObjectID );
			$sshPrivateKeyPassphrase = get_post_meta( $keyObjectID, GitKeyMetaBox::constructMetaKey( GitKeyMetaBox::NAME, GitKeyMetaBox::PASSPHRASE ), TRUE );
			$test                    = apply_filters( GitRepositoryProxy::FILTER_TEST, $repositoryPath, $gitSSH, $branch, $sshPrivateKey, $sshPrivateKeyPassphrase );
			if ( $test )
			{
				update_post_meta( $postID, StatusMetaField::NAME, StatusMetaField::OK );
			}
			else
			{
				update_post_meta( $postID, StatusMetaField::NAME, StatusMetaField::ERROR );
			}
		}
	}