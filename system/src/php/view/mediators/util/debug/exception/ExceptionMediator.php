<?php
namespace tutomvc;

class ExceptionMediator extends Mediator
{
	const NAME = "debug/exception.php";

	
	function __construct()
	{
		parent::__construct( self::NAME );
	}
}