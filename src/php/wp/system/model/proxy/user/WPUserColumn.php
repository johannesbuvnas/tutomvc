<?php
namespace tutomvc\wp;

class WPUserColumn extends ValueObject
{
	protected $_sortable;

	function __construct( $name, $title, $sortable = FALSE )
	{
		parent::__construct( $name, $title );
		$this->setSortable( $sortable );
	}

	/* METHODS */
	public function getContent( $default, $userID )
	{
		return $default;
	}

	public function sort( $wpUserQuery )
	{
		return $wpUserQuery;
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
