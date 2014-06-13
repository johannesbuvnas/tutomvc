<?php
namespace tutomvc;

class WPAdminTaxonomyColumn extends ValueObject
{
	function __construct( $name, $title )
	{
		parent::__construct( $name, $title );
	}

	/* METHODS */
	public function render( $termID )
	{
	}
}
