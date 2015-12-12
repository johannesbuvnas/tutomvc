<?php
namespace tutomvc\wp;

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
	public function getDBValue()
	{
		global $wpdb;

		$query = "
			SELECT {$wpdb->options}.option_value
			FROM {$wpdb->options}
			WHERE {$wpdb->options}.option_name = '".$this->getPostID()."'
		";

		$myrows = $wpdb->get_results( $query );
		$dp = array();
		foreach($myrows as $row) $dp[] = $row->option_value;
		
		return $dp;
	}
}