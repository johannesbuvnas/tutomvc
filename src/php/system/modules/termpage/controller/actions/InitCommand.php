<?php namespace tutomvc\modules\termpage;
use \tutomvc\ActionCommand;

class InitCommand extends ActionCommand
{
	const NAME = "init";

	function __construct()
	{
		parent::__construct( self::NAME );
	}

	function execute()
	{
		$this->getFacade()->controller->registerCommand( new PreGetPostsAction() );
		foreach(get_taxonomies() as $taxonomyName)
		{
			$this->getFacade()->controller->registerCommand( new TaxonomyAddFormFieldsAction( $taxonomyName ) );
		}
		$this->getFacade()->controller->registerCommand( new RegisteredTaxonomyAction() );
		$this->getFacade()->controller->registerCommand( new GetPageLinkFilter() );
	}
}
