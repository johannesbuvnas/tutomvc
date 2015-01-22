<?php
namespace tutomvc;

class PrepareMetaFieldCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct( ActionCommand::PREPARE_META_FIELD );

		$this->setExecutionLimit( 1 );
	}

	function execute()
	{
		$this->getFacade()->controller->registerCommand( new RenderWPEditorCommand() );
	}
}