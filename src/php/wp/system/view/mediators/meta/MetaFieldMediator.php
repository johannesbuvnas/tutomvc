<?php
namespace tutomvc\wp;

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

		$this->flush();

		return $this;
	}

	/* SET AND GET */
	public function setMetaField( MetaField $metaField )
	{
		$this->_metaField = $metaField;

		return $this;
	}
	public function getMetaField()
	{
		return $this->_metaField;
	}
}