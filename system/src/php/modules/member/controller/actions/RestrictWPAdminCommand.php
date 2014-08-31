<?php
namespace tutomvc\modules\member;
use \tutomvc\ActionCommand;

class RestrictWPAdminCommand extends ActionCommand
{
	const NAME = "tutomvc/modules/member/actions/restrict_wp_admin";

	function __construct()
	{
		parent::__construct( self::NAME );
	}

	function execute()
	{
		global $current_user;

		if(basename($_SERVER['PHP_SELF']) != 'admin-ajax.php')
		{
			if(!PrivacySettings::isWPAdminAllowed()) $this->redirect();
		}
	}

	function redirect()
	{
		wp_redirect( home_url() );
		exit;
	}
}