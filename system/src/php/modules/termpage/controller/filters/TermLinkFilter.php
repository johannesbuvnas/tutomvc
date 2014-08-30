<?php namespace tutomvc\modules\termpage;
use \tutomvc\FilterCommand;

class TermLinkFilter extends FilterCommand
{
	const NAME = "term_link";

	function __construct()
	{
		parent::__construct( self::NAME, 0, 3 );
	}

	function execute( $link, $term, $taxonomy )
	{
		$page = TermPageModule::getLandingPageForTerm( $term->term_taxonomy_id );
		if($page) 
		{
			$link = get_permalink( $page->ID );
		}

		return $link;
	}
}