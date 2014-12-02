<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/11/14
	 * Time: 15:09
	 */

	namespace tutomvc\modules\git;

	use tutomvc\Notification;
	use tutomvc\PostType;
	use tutomvc\TutoMVC;

	class GitWebhookPostType extends PostType
	{
		const NAME = "git-webhook";

		function __construct()
		{
			$labels = array(
				'name'          => __( "Git Webhook", TutoMVC::NAME ),
				'singular_name' => __( "Git Webhook", TutoMVC::NAME ),
				'menu_name'     => __( "Git Webhook", TutoMVC::NAME ),
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
				'public'             => TRUE,
				'publicly_queryable' => TRUE,
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

			$this->addColumn( new RevisionColumn() );

			add_filter( "single_template", array($this, "wp_filter_single_template"), 0, 1 );
		}

		public function wp_filter_single_template( $template )
		{
			global $post;
			if ( $post->post_type == self::NAME )
			{
				/**
				 * TODO: Only deploy if secret is provided.
				 */
				do_action( GitWebhookProxy::ACTION_DEPLOY, $post->ID );
				exit;
			}
		}
	}