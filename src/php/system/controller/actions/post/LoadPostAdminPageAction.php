<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 26/11/14
	 * Time: 13:34
	 */

	namespace tutomvc;

	class LoadPostAdminPageAction extends ActionCommand
	{

		const NAME = "load-post.php";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function execute()
		{
			global $current_screen;
			if ( $this->getFacade()->postTypeCenter->has( $current_screen->post_type ) )
			{
				$this->getFacade()->postTypeCenter->get( $current_screen->post_type )->action_load_post();
			}
		}

	}