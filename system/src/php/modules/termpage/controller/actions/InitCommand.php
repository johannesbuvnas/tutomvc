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
	}
}
