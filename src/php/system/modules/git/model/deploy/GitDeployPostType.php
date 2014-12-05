<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 24/11/14
	 * Time: 15:50
	 */

	namespace tutomvc\modules\git;

	use tutomvc\PostType;
	use tutomvc\TutoMVC;

	class GitDeployPostType extends PostType
	{
		const NAME = "git-deploy";

		function __construct()
		{
			$labels = array(
				'name'          => __( "Git Deploy", TutoMVC::NAME ),
				'singular_name' => __( "Git Deploy", TutoMVC::NAME ),
				'menu_name'     => __( "Git Deploy", TutoMVC::NAME ),
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
			$this->addColumn( new GitDeployStageColumn() );
			$this->addColumn( new ProgressionColumn() );
		}

		function wp_filter_default_title( $title, $post )
		{
			return __( "Deployed manually", TutoMVC::NAME );
		}
	}