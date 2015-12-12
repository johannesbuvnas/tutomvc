<?php
namespace tutomvc\wp;

class JSGlobalMediator extends Mediator
{
	const NAME = "head/js-global.php";

	function __construct()
	{
		parent::__construct( self::NAME );
	}
}