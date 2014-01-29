<?php
namespace tutomvc;

class ExceptionMediator extends Mediator
{
	function __construct()
	{
		parent::__construct( "debug/exception.php" );
	}
}