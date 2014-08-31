<?php
namespace tutomvc\modules\analytics;
use \tutomvc\Mediator;

class AnalyticsMediator extends Mediator
{
	const NAME = "modules/analytics/universal.php";

	function __construct()
	{
		parent::__construct( self::NAME );
	}

	function onRegister()
	{
	}
}