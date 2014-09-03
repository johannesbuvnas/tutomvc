<?php namespace tutomvc\modules\termpage;
use \tutomvc\ActionCommand;

/**
*	Prioritize pages rather than terms with exact same slug.
*/
class PreGetPostsAction extends ActionCommand
{
	const NAME = "pre_get_posts";
	const QUERY_VAR = "tutomvc_modules_termpage_pre_get_posts_filtered";

	function __construct()
	{
		parent::__construct( self::NAME, 0 );

		$this->setExecutionLimit( 1 );
	}

	function execute( $wpQuery )
	{
		global $wp_query;
		// Forever loop fix
		// Do not execute this action for copied wp queries
		if(!is_main_query()) return;
		if(array_key_exists(self::QUERY_VAR, $wpQuery->query_vars)) return;

		if(is_tax() || get_query_var( "tag" ) || is_category())
		{
			if(is_tax())
			{
				$taxonomy = get_taxonomy( get_query_var( 'taxonomy' ) );
				$by = filter_var(get_query_var( 'term' ), FILTER_VALIDATE_INT) ? "id" : "slug";
				$term = get_term_by( $by, get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
			}
			else if(is_category())
			{
				$taxonomy = get_taxonomy( "category" );
				$by = filter_var(get_query_var( 'term' ), FILTER_VALIDATE_INT) ? "id" : "slug";
				$term = get_term_by( $by, get_query_var( 'category_name' ), "category" );
			}
			else if(get_query_var( "tag" ))
			{
				$taxonomy = get_taxonomy( "post_tag" );
				$by = filter_var(get_query_var( 'tag' ), FILTER_VALIDATE_INT) ? "id" : "slug";
				$term = get_term_by( $by, get_query_var( 'tag' ), "post_tag" );
			}

			if(!$term)
			{
				foreach(get_taxonomies() as $taxonomy)
				{
					if(get_query_var( $taxonomy ))
					{
						$by = filter_var(get_query_var( $taxonomy ), FILTER_VALIDATE_INT) ? "id" : "slug";
						$term = get_term_by( $by, get_query_var( $taxonomy ), $taxonomy );
						break;
					}
				}
			}

			if($term)
			{
				$associatedPage = TermPageModule::getLandingPageForTerm( $term->term_taxonomy_id );

				if($associatedPage && get_query_var("paged") < 2)
				{
					query_posts(array(
						"page_id" => $associatedPage->ID,
						self::QUERY_VAR => $wp_query->query_vars
					));
				}
			}
		}
		else if(is_page())
		{
			$queried_object = get_queried_object();
			$term = TermPageModule::getTermForLandingPage( $queried_object->ID );
			if($term)
			{
				wp_redirect( get_term_link( $term ) );
				exit;
			}
		}
	}
}