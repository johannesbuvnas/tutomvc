<?php
namespace tutomvc\modules\privacy;
use \tutomvc\FilterCommand;

class ShowAdminBarFilterCommand extends FilterCommand
{
	function __construct()
	{
		parent::__construct("show_admin_bar");
	}

	function execute()
	{
		$defaultValue = $this->getArg(0);

		if(!PrivacySettings::isWPAdminAllowed()) return FALSE;
		else return $defaultValue;
	}
}