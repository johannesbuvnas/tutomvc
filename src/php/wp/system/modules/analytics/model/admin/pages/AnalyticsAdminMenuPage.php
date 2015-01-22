<?php
namespace tutomvc\modules\analytics;
use \tutomvc\AdminMenuSettingsPage;

class AnalyticsAdminMenuPage extends AdminMenuSettingsPage
{
	const NAME = "custom_analytics_settings";

	function __construct()
	{
		parent::__construct(
			"Google Analytics",
			"Google Analytics",
			"edit_theme_options",
			self::NAME,
			NULL,
			NULL
		);

		$this->setType( AdminMenuSettingsPage::TYPE_OPTIONS );
	}
}