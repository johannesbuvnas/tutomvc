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
	const TYPE_SELECTOR_SWITCH = "selector_switch";
	const TYPE_SELECTOR_DATETIME = "selector_datetime";

	// Settings
	const SETTING_DIVIDER_BEFORE = "dividerBefore";
	const SETTING_DIVIDER_AFTER = "dividerAfter";
	const SETTING_TITLE = "title";
	const SETTING_OPTIONS = "options";
	const SETTING_MAX_CARDINALITY = "maxCardinality";
	const SETTING_FILTER = "filter"; // Used by attachment
	const SETTING_BUTTON_TITLE = "buttonTitle"; // Used by attachment
	const SETTING_DEFAULT_VALUE = "defaultValue";

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
	public function isSingle()
	{
		return $this->getMaxCardinality() == 1;
	}

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
	public function isSingle();

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