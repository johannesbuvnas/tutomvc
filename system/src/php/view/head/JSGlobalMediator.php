<?php
namespace tutomvc;

class JSGlobalMediator extends Mediator
{
	const NAME = "head/js-global.php";

	function __construct()
	{
		parent::__construct( self::NAME );
	}
}