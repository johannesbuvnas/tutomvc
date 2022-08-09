<?php
	namespace TutoMVC\WordPress\Utils;

	class WPUtil
	{
		/**
		 * @param string $separator
		 *
		 * @return null|string
		 */
		public static function formatPageTitle( $separator = "·" )
		{
			global $page, $paged;

			$title = wp_title( $separator, FALSE, 'right' );

			// Add the blog name.
			$title .= get_bloginfo( 'name' );

			// Add the blog description for the home/front page.
			$site_description = get_bloginfo( 'description', 'display' );
			if ( $site_description && (is_home() || is_front_page()) )
				$title .= " | $site_description";

			// Add a page number if necessary:
			if ( $paged >= 2 || $page >= 2 )
				$title .= ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

			return $title;
		}

		public static function formatDate( $date, $dateFormat = NULL )
		{
			if ( !$dateFormat ) $dateFormat = get_option( "date_format" );

			return apply_filters( 'get_the_date', mysql2date( $dateFormat, $date ) );
		}
	}
