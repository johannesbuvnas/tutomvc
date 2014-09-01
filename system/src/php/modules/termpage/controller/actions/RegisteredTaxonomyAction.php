<?php namespace tutomvc\modules\termpage;
use \tutomvc\ActionCommand;

class RegisteredTaxonomyAction extends ActionCommand
{
	const NAME = "registered_taxonomy";

	function __construct()
	{
		parent::__construct( self::NAME, 0, 3 );
	}

	function execute( $taxonomy, $object_type, $args )
	{
		if(!$this->getFacade()->controller->hasCommand( $taxonomy . TaxonomyAddFormFieldsAction::NAME ))
		{
			$this->getFacade()->controller->registerCommand( new TaxonomyAddFormFieldsAction( $taxonomy ) );
		}
	}
}
