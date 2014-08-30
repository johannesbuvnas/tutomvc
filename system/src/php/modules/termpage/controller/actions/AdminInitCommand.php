<?php namespace tutomvc\modules\termpage;
use \tutomvc\ActionCommand;

class AdminInitCommand extends ActionCommand
{
	const NAME = "admin_init";

	function __construct()
	{
		parent::__construct( self::NAME );
	}

	function execute()
	{
		foreach($this->getFacade()->getTaxonomies() as $taxonomyName)
		{
			$this->getFacade()->controller->registerCommand( new TaxonomyAddFormFieldsAction( $taxonomyName ) );
		}

		$this->getFacade()->controller->registerCommand( new EditedTermAction() );
		$this->getFacade()->controller->registerCommand( new LoadPostCommand() );
	}
}
