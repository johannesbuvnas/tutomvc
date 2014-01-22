<?php
namespace tutomvc;

class AdminMenuSettingsPageMediator extends Mediator
{
	const NAME = "menu/admin-menu-settings-page.php";


	function __construct()
	{
		parent::__construct( self::NAME );
	}
}