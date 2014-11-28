<?php
namespace tutomvc\modules\privacy;
use \tutomvc\ActionCommand;

class PreGetPostsAction extends ActionCommand
{
	const NAME = "pre_get_posts";
	const QUERY_VAR = "tutomvc_modules_privacy";

	public static $prohibitExecution = FALSE;

	function __construct()
	{
		parent::__construct( self::NAME, 9999 );
	}

	function execute()
	{
		$wpQuery = $this->getArg( 0 );


		// Forever loop fix
		// Do not execute this action for copied wp queries
		if(self::$prohibitExecution) return;


		// Run the query_vars in a new wp_query and loop the post results, remove the blocked posts from the query
		$vars = array_merge( $wpQuery->query_vars, array(
			"nopaging" => TRUE // Pagination fix
		) );
		self::$prohibitExecution = TRUE;
		$copyWpQuery = new \WP_Query( $vars );
		self::$prohibitExecution = FALSE;

		$blockedPosts = array();
		foreach($copyWpQuery->posts as $post)
		{
			if(!PrivacyMetaBox::isUserAllowed( NULL, $post->ID )) $blockedPosts[] = $post->ID;
		}

		if(count($blockedPosts))
		{
			$wpQuery->set( self::QUERY_VAR, TRUE );
			$wpQuery->set( "post__not_in", array_merge( $wpQuery->get( "post__not_in" ), $blockedPosts ) );
		}
	}
}