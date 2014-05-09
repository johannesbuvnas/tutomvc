<?php
namespace tutomvc;

class WPAdminPostTypeColumn extends ValueObject
{
	protected $_sortable;

	function __construct( $name, $title, $sortable = FALSE )
	{
		parent::__construct( $name, $title );
		$this->setSortable( $sortable );
	}

	/* METHODS */
	public function render( $postID )
	{
	}

	public function sort( $wpQuery )
	{
		return $wpQuery;
	}

	/* SET AND GET */
	public function setSortable( $value )
	{
		$this->_sortable = $value;
		return $this;
	}
	public function getSortable()
	{
		return $this->_sortable;
	}
}
