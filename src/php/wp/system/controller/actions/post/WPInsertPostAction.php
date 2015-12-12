<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 28/11/14
	 * Time: 20:50
	 */

	namespace tutomvc\wp;

	class WPInsertPostAction extends ActionCommand
	{
		const NAME = "wp_insert_post";

		function __construct()
		{
			parent::__construct( self::NAME, 0, 3 );
		}

		function execute( $postID, $post, $update )
		{
			if ( $this->getFacade()->postTypeCenter->has( $post->post_type ) )
			{
				$this->getFacade()->postTypeCenter->get( $post->post_type )->wp_action_wp_insert_post( $postID, $post, $update );
			}
		}

	}