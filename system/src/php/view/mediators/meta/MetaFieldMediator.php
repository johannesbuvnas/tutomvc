<?php
namespace tutomvc;

class MetaFieldMediator extends Mediator
{
	const NAME = "meta/meta-field.php";

	private $_metaField;

	private $_inputMediator;

	function __construct()
	{
		parent::__construct( self::NAME );
	}

	function render()
	{
		$this->parse( "metaField", $this->_metaField );

		parent::render();
	}

	/* SET AND GET */
	public function setMetaField( MetaField $metaField )
	{
		$this->_metaField = $metaField;
	}
	public function getMetaField()
	{
		return $this->_metaField;
	}
}