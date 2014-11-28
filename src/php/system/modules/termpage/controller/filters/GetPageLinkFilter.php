<?php namespace tutomvc\modules\termpage;
use \tutomvc\FilterCommand;

class GetPageLinkFilter extends FilterCommand
{
	const NAME = "page_link";

	function __construct()
	{
		parent::__construct( self::NAME, 0, 3 );
	}

	function execute( $link, $postID, $sample )
	{
		$term = TermPageModule::getTermForLandingPage( $postID );

		if($term)
		{
			$link = get_term_link( $term );
		}

		return $link;
	}
}
