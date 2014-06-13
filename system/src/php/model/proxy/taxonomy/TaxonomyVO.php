<?php
namespace tutomvc;

class TaxonomyVO extends MetaVO
{
	protected $taxName;

	function __construct( $taxName, $name, $postID, $metaField )
	{
		$this->taxName = $taxName;
		parent::__construct( $name, $postID, $metaField );
	}
	/* SET AND GET */
	public function setValue( $value )
	{
		return FALSE;
	}
	public function getValue()
	{
		$terms = wp_get_post_terms( $this->getPostID(), $this->taxName );
		$value = array();
		foreach($terms as $term) $value[] = (string)$term->name;

		return $value;
	}
	public function getDBValue()
	{
		return $this->getValue();
	}
}