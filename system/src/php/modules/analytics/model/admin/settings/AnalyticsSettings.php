<?php
namespace tutomvc\modules\analytics;
use \tutomvc\Settings;
use \tutomvc\SettingsField;

class AnalyticsSettings extends Settings
{
	const NAME = "custom_analytics_options";
	const ACCOUNT_ID = "custom_analytics_account_id";
	const GTM_ACCOUNT_ID = "custom_analytics_gtm_account_id";

	function __construct()
	{
		parent::__construct(
			self::NAME,
			AnalyticsAdminMenuPage::NAME,
			""
		);

		$this->addSettingsField( new SettingsField( 
			self::ACCOUNT_ID,
			__( "Google Analytics Account ID" ), "",
			SettingsField::TYPE_TEXT
		) );

		$this->addSettingsField( new SettingsField( 
			self::GTM_ACCOUNT_ID,
			__( "Google Tag Manager Account ID" ), "",
			SettingsField::TYPE_TEXT
		) );
	}
}