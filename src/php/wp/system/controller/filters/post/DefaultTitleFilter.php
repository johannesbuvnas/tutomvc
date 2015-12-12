<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 26/11/14
	 * Time: 10:35
	 */

	namespace tutomvc\wp;

	class DefaultTitleFilter extends FilterCommand
	{

		const NAME = "default_title";

		function __construct()
		{
			parent::__construct( self::NAME, 0, 2 );
		}

		function execute( $title, $post )
		{
			if ( $this->getFacade()->postTypeCenter->has( $post->post_type ) )
			{
				return $this->getFacade()->postTypeCenter->get( $post->post_type )
				                                         ->wp_filter_default_title( $title, $post );
			}

			return $title;
		}

	}