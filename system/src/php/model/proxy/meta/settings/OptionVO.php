<?php
namespace tutomvc;

class OptionVO extends MetaVO
{
	function __construct( $sectionField )
	{
		parent::__construct( $sectionField->getName(), 0, $sectionField );
	}

	/* SET AND GET */
	public function setValue( $value )
	{
		return update_option( $this->getName(), $value );
	}
	public function getValue()
	{
		return get_option( $this->getName() );
	}
}