<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 26/11/14
	 * Time: 10:35
	 */

	namespace tutomvc\wp;

	class WPInsertPostDataFilter extends FilterCommand
	{

		const NAME = "wp_insert_post_data";

		function __construct()
		{
			parent::__construct( self::NAME, 0, 2 );
		}

		function execute( $data, $postArray )
		{
			if ( $this->getFacade()->postTypeCenter->has( $data[ 'post_type' ] ) )
			{
				return $this->getFacade()->postTypeCenter->get( $data[ 'post_type' ] )
				                         ->filter_wp_insert_post_data( $data, $postArray );
			}

			return $data;
		}

	}