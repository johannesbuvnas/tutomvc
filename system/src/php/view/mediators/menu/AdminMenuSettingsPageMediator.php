<?php
namespace tutomvc;

class AdminMenuSettingsPageMediator extends AdminMenuPageMediator
{
	const NAME = "menu/settings/admin-menu-settings-page.php";


	function __construct()
	{
		parent::__construct( self::NAME );
	}

	function getContent()
	{
		$this->parse( "adminMenuPage", $this->getAdminMenuPage() );

		return parent::getContent();
	}
}