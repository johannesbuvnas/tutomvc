<?php
namespace tutomvc\modules\privacy;
use \tutomvc\AdminMenuSettingsPage;

class PrivacySettingsAdminMenuPage extends AdminMenuSettingsPage
{
	const NAME = "custom_privacy_settings";

	function __construct()
	{
		parent::__construct(
			__("Privacy"),
			__("Privacy"),
			"edit_theme_options",
			self::NAME,
			NULL,
			NULL
		);

		$this->setType( AdminMenuSettingsPage::TYPE_OPTIONS );
	}

	public function onLoad()
	{
	}
}