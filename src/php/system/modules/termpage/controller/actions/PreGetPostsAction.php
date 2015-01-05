<?php namespace tutomvc\modules\termpage;
use \tutomvc\ActionCommand;
use \tutomvc\Taxonomy;

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

		if ( is_page() )
		{
			$this->isPage( get_page( get_query_var( "page_id" ) ) );
		}

		foreach(get_taxonomies() as $taxonomyName)
		{
			$taxonomyObject = get_taxonomy( $taxonomyName );
			if(get_query_var( $taxonomyObject->query_var ))
			{
				$by = filter_var(get_query_var( $taxonomyObject->query_var ), FILTER_VALIDATE_INT) ? "id" : "slug";
				$term = get_term_by( $by, get_query_var( $taxonomyObject->query_var ), $taxonomyName );

//				if($term)
//				{
//					$page = TermPageModule::getLandingPageForTerm( $term->term_taxonomy_id );
//					if($page)
//					{
//						return query_posts(array(
//							"page_id" => $page->ID
//						));
//					}
//				}

				if(!$term)
				{
					// Maybe this is not a term, but a page with the same slug structure as a taxonomy
					$page = get_page_by_path( Taxonomy::getTaxonomyRewriteSlug( $taxonomyName ) . "/" . get_query_var( $taxonomyObject->query_var ) );
					if($page)
					{
						query_posts(array(
							"page_id" => $page->ID
						));
						return;
					}
				}
				break;
			}
		}

		if(isset($term))
		{
			$this->isTerm( $term );
		}
	}

	public function isTerm( $term )
	{
		if(!$term) return;
		global $wp_query;

		$associatedPage = TermPageModule::getLandingPageForTerm( $term->term_taxonomy_id );

		if($associatedPage && get_query_var("paged") < 2)
		{
			query_posts(array(
				"page_id" => $associatedPage->ID,
				self::QUERY_VAR => $wp_query->query_vars
			));
		}
	}

	public function isPage( $page )
	{
		if(!$page) return;

		$term = TermPageModule::getTermForLandingPage( $page->ID );

		if($term)
		{
			wp_redirect( get_term_link( $term ) );
			exit;
		}
	}
}