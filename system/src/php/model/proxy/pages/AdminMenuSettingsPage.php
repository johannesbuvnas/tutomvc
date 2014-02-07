<?php
namespace tutomvc;

class AdminMenuSettingsPage extends AdminMenuPage
{
	/* VARS */
	protected $_settingsName;


	function __construct( $pageTitle, $menuTitle, $capability, $menuSlug, $menuIconURL = NULL, $menuPosition = NULL )
	{
		parent::__construct( $pageTitle, $menuTitle, $capability, $menuSlug, $menuIconURL, $menuPosition );
	}
}