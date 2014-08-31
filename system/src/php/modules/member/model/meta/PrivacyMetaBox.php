<?php
namespace tutomvc\modules\member;
use \tutomvc\MetaBox;
use \tutomvc\MetaField;
use \tutomvc\MetaCondition;

class PrivacyMetaBox extends MetaBox
{
	const NAME = "custom_privacy";
	const IS_PROTECTED = "is_protected";
	const IS_PROTECTED_INHERIT = "inherit";
	const IS_PROTECTED_TRUE = "true";
	const IS_PROTECTED_FALSE = "false";
	const ALLOWED_USER_TYPES = "allowed_user_types";
	const ALLOWED_USER_TYPES_ALL = "allowed_user_types_all";

	static $supportedPostTypes = array( "page", "post" );

	function __construct()
	{
		parent::__construct( self::NAME, __( "Sharing" ), self::$supportedPostTypes, 1, MetaBox::CONTEXT_SIDE, MetaBox::PRIORITY_CORE );

		$this->addField( new MetaField( self::IS_PROTECTED, __( "Visibility", "tutomvc" ), "", MetaField::TYPE_SELECTOR_SINGLE, array(
				MetaField::SETTING_OPTIONS => array(
					self::IS_PROTECTED_INHERIT => __( "Inherit" ),
					self::IS_PROTECTED_TRUE => __( "Private - only allowed user types" ),
					self::IS_PROTECTED_FALSE => __( "Public on the web" )
				),
				MetaField::SETTING_DEFAULT_VALUE => self::IS_PROTECTED_INHERIT
			) ) );

		$allowedUserTypes = array(
			self::ALLOWED_USER_TYPES_ALL => __("All")
		);
		global $wp_roles;
		foreach($wp_roles->roles as $role => $value)
		{
			$allowedUserTypes[$role] = $value['name'];
		}

		$this->addField( new MetaField( 
			self::ALLOWED_USER_TYPES,
			__( "Allowed User Types" ), "",
			MetaField::TYPE_SELECTOR_MULTIPLE,
			array(
				MetaField::SETTING_OPTIONS => $allowedUserTypes,
				MetaField::SETTING_DEFAULT_VALUE => self::ALLOWED_USER_TYPES_ALL,
				MetaField::SETTING_LABEL => __( "Allowed User Types" )
			),
			array(
				new MetaCondition( 
					self::NAME,
					self::IS_PROTECTED,
					self::IS_PROTECTED_TRUE,
					MetaCondition::CALLBACK_SHOW,
					MetaCondition::CALLBACK_HIDE
				)
			)
		) );

		$this->setMinCardinality( 1 );
	}

	public static function isUserAllowed($user = NULL, $postID = NULL)
	{
		if(!$user)
		{
			global $current_user;
			$user = $current_user;
		}

		global $post;
		if(!$postID && $post) $postID = $post->ID;

		if(!in_array( get_post_type( $postID ), self::$supportedPostTypes)) return PrivacySettings::isUserAllowed($user);

		$protection = get_post_meta( $postID, self::constructMetaKey( self::NAME, self::IS_PROTECTED ) );
		switch($protection)
		{
			case self::IS_PROTECTED_INHERIT:

				$ancestors = get_post_ancestors( $postID );
				if(count($ancestors))
				{
					foreach($ancestors as $ancestor) return self::isUserAllowed( $user, $ancestor );
				}
				else
				{
					// Controlled by theme
					return PrivacySettings::isUserAllowed( $user );
				}

			break;
			case self::IS_PROTECTED_TRUE:

				// Filter out allowed user types
				if(!is_user_logged_in()) return FALSE;

				$roleRestriction = get_post_meta( $postID, self::constructMetaKey( self::NAME, self::ALLOWED_USER_TYPES ));
				if(is_string($roleRestriction)) $roleRestriction = array( $roleRestriction );
				if(in_array(self::ALLOWED_USER_TYPES_ALL, $roleRestriction)) return TRUE;
				if(!count($roleRestriction)) return FALSE;
				foreach($user->roles as $role)
				{
					if(in_array($role, $roleRestriction)) return TRUE;
				}

				return FALSE;

			break;
			case self::IS_PROTECTED_FALSE:

				// Controlled by theme
				return TRUE;

			break;
		}

		return PrivacySettings::isUserAllowed($user);
	}
}
