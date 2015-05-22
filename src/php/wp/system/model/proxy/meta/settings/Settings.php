<?php
namespace tutomvc;

class Settings extends MetaBox
{

	/* VARS */
	protected $_menuSlug;
	protected $_registerAsNewSection = TRUE;
	protected $_description;


	function __construct( $name, $pageName, $title = NULL, $description = NULL  )
	{
		$this->setName( $name );
		$this->setMenuSlug( $pageName );
		$this->setTitle( $title );
		$this->setDescription( $description );
	}

	/* ACTIONS */
	public function renderDescription( $args )
	{
		if(is_string($this->getDescription()))
		{
			echo "<span class='description'>".$this->getDescription()."</span>";
		}
	}

	/* METHODS */
	public function addField( MetaField $field )
	{
		$field->setSetting( MetaField::SETTING_AUTOLOAD, TRUE );
		return parent::addField( $field );
	}
	
	/* SET AND GET */
	/**
	*	Which settings page should these settings be visible on.
	*/
	public function setMenuSlug( $value )
	{
		$this->_menuSlug = $value;
	}
	public function getMenuSlug()
	{
		return $this->_menuSlug;
	}

	public function setDescription( $value )
	{
		$this->_description = $value;
	}
	public function getDescription()
	{
		return $this->_description;
	}

	public function setRegisterAsNewSection( $value )
	{
		$this->_registerAsNewSection = $value;
	}
	public function getRegisterAsNewSection()
	{
		return $this->_registerAsNewSection;
	}
}