<?php
namespace tutomvc\modules\analytics;
use \tutomvc\Mediator;

class GTMMediator extends Mediator
{
	const NAME = "modules/analytics/gtm.php";

	function __construct()
	{
		parent::__construct( self::NAME );
	}

	function onRegister()
	{
	}
}