<?php
namespace tutons;

class FilterCommand extends Command
{
	/* TYPES */
	const META_VALUE = "tuto/filter/meta/value";
	
	/* PUBLIC VARS */
	public $priority = 10;
	public $acceptedArguments = 1;

	function __construct( $name = NULL, $acceptedArguments = 1 )
	{
		parent::__construct( $name );
		$this->acceptedArguments = $acceptedArguments;
	}


	public function register()
	{
		add_filter( $this->getName(), array( $this, "preExecution" ), $this->priority, $this->acceptedArguments );
	}
}