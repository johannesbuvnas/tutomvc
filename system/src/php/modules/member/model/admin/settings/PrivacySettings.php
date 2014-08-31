<?php
namespace tutomvc\modules\member;
use \tutomvc\Settings;
use \tutomvc\SettingsField;

class PrivacySettings extends Settings
{
	const NAME = "custom_privacy_settings_options";
	const IS_PROTECTED = "custom_privacy_settings_options_is_protected";
	const ALLOWED_USER_TYPES = "custom_privacy_settings_options_allowed_user_types";
	const WP_ADMIN_ALLOWED_USER_TYPES = "custom_privacy_settings_options_wp_admin_allowed_user_types";
	const ALLOWED_USER_TYPES_ALL = "custom_privacy_settings_options_allowed_user_types_all";

	function __construct()
	{
		parent::__construct(
			self::NAME,
			PrivacySettingsAdminMenuPage::NAME,
			""
		);

		$this->addSettingsField( new SettingsField( 
			self::IS_PROTECTED,
			__( "Restrict Blog", "tutomvc" ), __( "Restrict this website to specific user roles.", "tutomvc" ),
			SettingsField::TYPE_SELECTOR_SINGLE,
			array(
				SettingsField::SETTING_OPTIONS => array(
					"true" => __( "Yes" ),
					"false" => __( "No" )
				),
				SettingsField::SETTING_DEFAULT_VALUE => "false"
			)
		) );

		$allowedUserTypes = array(
			self::ALLOWED_USER_TYPES_ALL => __("All")
		);
		global $wp_roles;
		foreach($wp_roles->roles as $role => $value)
		{
			$allowedUserTypes[$role] = $value['name'];
		}

		$this->addSettingsField( new SettingsField( 
			self::ALLOWED_USER_TYPES,
			__( "Allowed User Roles", "tutomvc" ), "",
			SettingsField::TYPE_SELECTOR_MULTIPLE,
			array(
				SettingsField::SETTING_OPTIONS => $allowedUserTypes,
				SettingsField::SETTING_DEFAULT_VALUE => self::ALLOWED_USER_TYPES_ALL,
				SettingsField::SETTING_LABEL => __( "Select User Roles", "tutomvc" )
			)
		) );

		$this->addSettingsField( new SettingsField( 
			self::WP_ADMIN_ALLOWED_USER_TYPES,
			__( "Restrict WP Admin Area", "tutomvc" ), "",
			SettingsField::TYPE_SELECTOR_MULTIPLE,
			array(
				SettingsField::SETTING_OPTIONS => $allowedUserTypes,
				SettingsField::SETTING_DEFAULT_VALUE => self::ALLOWED_USER_TYPES_ALL,
				SettingsField::SETTING_LABEL => __( "Select User Roles", "tutomvc" )
			)
		) );
	}

	public static function isWPAdminAllowed($user = NULL)
	{
		if(!$user)
		{
			global $current_user;
			$user = $current_user;
		}

		$wpAdminRestriction = get_option( self::WP_ADMIN_ALLOWED_USER_TYPES );
		if(is_string($wpAdminRestriction)) $wpAdminRestriction = array( $wpAdminRestriction );
		if(in_array(PrivacySettings::ALLOWED_USER_TYPES_ALL, $wpAdminRestriction)) return TRUE;
		if(!$user) return FALSE;
		if(!count($wpAdminRestriction)) return TRUE;

		foreach($user->roles as $role)
		{
			if(in_array($role, $wpAdminRestriction)) return TRUE;
		}

		return FALSE;
	}

	public static function isUserAllowed($user = NULL)
	{
		if(!$user)
		{
			global $current_user;
			$user = $current_user;
		}

		if(!filter_var( get_option( self::IS_PROTECTED ), FILTER_VALIDATE_BOOLEAN )) return TRUE;
		if(!is_user_logged_in() && filter_var( get_option( self::IS_PROTECTED ), FILTER_VALIDATE_BOOLEAN )) return FALSE;

		$roleRestriction = get_option( self::ALLOWED_USER_TYPES );
		// if(is_string($roleRestriction) && $roleRestriction == "false") return TRUE;
		if(is_string($roleRestriction)) $roleRestriction = array( $roleRestriction );
		if(in_array(PrivacySettings::ALLOWED_USER_TYPES_ALL, $roleRestriction)) return TRUE;
		if(!count($roleRestriction)) return TRUE;

		foreach($user->roles as $role)
		{
			if(in_array($role, $roleRestriction)) return TRUE;
		}

		return FALSE;
	}
}