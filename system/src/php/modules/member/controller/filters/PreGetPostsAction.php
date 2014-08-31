<?php
namespace tutomvc\modules\member;
use \tutomvc\ActionCommand;

class PreGetPostsAction extends ActionCommand
{
	const NAME = "pre_get_posts";

	function __construct()
	{
		parent::__construct( self::NAME, 9999 );
	}

	function execute()
	{
		$wpQuery = $this->getArg( 0 );


		// Forever loop fix
		// Do not execute this action for copied wp queries
		if(array_key_exists("is_copy_wp_query", $wpQuery->query_vars)) return;


		$vars = array_merge( $wpQuery->query_vars, array(
			"is_copy_wp_query" => TRUE, // Anti loop fix
			"nopaging" => TRUE // Pagination fix
		) );
		$copyWpQuery = new \WP_Query( $vars );
		$blockedPosts = array();
		if(count($copyWpQuery->posts) <= 1) return; // Let the WPCommand do the rest
		foreach($copyWpQuery->posts as $post)
		{
			if(!PrivacyMetaBox::isUserAllowed( NULL, $post->ID )) $blockedPosts[] = $post->ID;
		}

		if(count($blockedPosts)) $wpQuery->set( "post__not_in", array_merge( $wpQuery->get( "post__not_in" ), $blockedPosts ) );
	}
}