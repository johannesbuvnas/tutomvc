<?php namespace tutomvc\modules\termpage;
use \tutomvc\ActionCommand;

class EditedTermAction extends ActionCommand
{
	const NAME = "edited_term";

	function __construct()
	{
		parent::__construct( self::NAME, 0, 3 );
	}

	function execute( $termID, $termTaxonomyID, $taxonomyName )
	{
		if($_POST && array_key_exists("page_id", $_POST))
		{
			TermPageModule::setLandingPageForTerm( $termTaxonomyID, $_POST['page_id'] );
		}
	}
}
