<?php
namespace tutomvc;

class MetaField extends ValueObject implements IMetaBoxField
{
	// Types
	const TYPE_TEXT = "text";
	const TYPE_TEXTAREA = "textarea";
	const TYPE_TEXTAREA_WYSIWYG = "textarea_wysiwyg";
	const TYPE_ATTACHMENT = "attachment";
	const TYPE_SELECTOR_SINGLE = "selector_single";
	const TYPE_SELECTOR_MULTIPLE = "selector_multiple";
	// const TYPE_SELECTOR_SWITCH = "selector_switch";
	const TYPE_SELECTOR_DATETIME = "selector_datetime";
	const TYPE_LINK = "link";
	const TYPE_TAXONOMY = "taxonomy";

	// Settings
	const SETTING_DIVIDER_BEFORE = "dividerBefore";
	const SETTING_DIVIDER_AFTER = "dividerAfter";
	const SETTING_DEFAULT_VALUE = "defaultValue";

	const SETTING_TITLE = "title"; // DEPRECATED
	const SETTING_READ_ONLY = "readOnly"; // Used by TYPE_TEXT, TYPE_TEXTAREA
	const SETTING_ROWS = "rows"; // Used by TYPE_TEXTAREA
	
	const SETTING_LABEL = "label"; // Used by TYPE_SELECTOR_SINGLE, TYPE_SELECTOR_MULTIPLE
	const SETTING_FORMAT = "format"; // Used by TYPE_SELECTOR_DATETIME
	const SETTING_OPTIONS = "options"; // Used by TYPE_SELECTOR_SINGLE, TYPE_SELECTOR_MULTIPLE
	const SETTING_MAX_CARDINALITY = "maxCardinality"; // Used by TYPE_ATTACHMENT
	const SETTING_FILTER = "filter"; // Used by TYPE_ATTACHMENT
	const SETTING_BUTTON_TITLE = "buttonTitle"; // Used by TYPE_ATTACHMENT

	const SETTING_TAXONOMY = "taxonomy"; // Used by TYPE_TAXONOMY
	const SETTING_TAXONOMY_TERMS = "taxonomy_terms"; // Used by TYPE_TAXONOMY

	/* VARS */
	private $_title;
	private $_description;
	private $_type;
	private $_settings;
	private $_conditions;

	public function __construct( 
		$name, 
		$title = "", 
		$description = "", 
		$type = MetaField::TYPE_TEXT, 
		$settings = array(), 
		$conditions = array()
	)
	{
		$this->setTitle( $title );
		$this->setDescription( $description );
		$this->setName( $name );
		$this->setType( $type );
		$this->setSettings( $settings );
		$this->setConditions( $conditions );
	}

	/* METHODS */
	public function addCondition( MetaCondition $condition )
	{
		$this->_conditions[] = $condition;
	}

	/* SET AND GET */
	public function setTitle( $title )
	{
		$this->_title = $title;
	}
	public function getTitle()
	{
		return $this->_title;
	}

	public function setDescription( $description )
	{
		$this->_description = $description;
	}
	public function getDescription()
	{
		return $this->_description;
	}

	public function setType( $type )
	{
		$this->_type = $type;
	}
	public function getType()
	{
		return $this->_type;
	}

	public function setSettings( $settings )
	{
		$this->_settings = $settings;
	}
	public function getSettings()
	{
		return $this->_settings;
	}
	public function setSetting( $name, $value )
	{
		$this->_settings[ $name ] = $value;

		return $this;
	}
	public function getSetting( $settingName )
	{
		if( $this->hasSetting($settingName) ) return $this->_settings[$settingName];
		else return NULL;
	}
	public function hasSetting( $settingName )
	{
		return is_array($this->_settings) ? array_key_exists($settingName, $this->_settings) : FALSE;
	}

	public function setConditions( $conditions )
	{
		$this->_conditions = $conditions;
	}
	public function getConditions()
	{
		return $this->_conditions;
	}
}

interface IMetaBoxField
{
	/* CONSTANTS */
	const CARDINALITY_SINGLE = 1;
	const CARDINALITY_UNLIMITED = -1;

	/* ACTIONS */

	/* METHODS */

	/* SET AND GET */
	public function setTitle( $title );
	public function getTitle();
	public function setDescription( $description );
	public function getDescription();
	public function setType( $type );
	public function getType();
	public function setSettings( $settings );
	public function getSettings();
	public function setConditions( $conditions );
	public function getConditions();
}