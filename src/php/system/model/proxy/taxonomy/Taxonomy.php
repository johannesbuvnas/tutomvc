<?php
namespace tutomvc;

class Taxonomy extends ValueObject
{

	protected $_supportedObjectTypes = array();
	private $_columnsMap = array();
	protected $_args = array();
	protected $_metaBoxTermsArgs = array();

	function __construct( $name, $supportedObjectTypes = array(), $args = array(), $metaBoxTermsArgs = array() )
	{
		$this->_args = $args;
		$this->_metaBoxTermsArgs = wp_parse_args( $metaBoxTermsArgs, array(
			"hide_empty" => FALSE
		) );

		$this->_supportedObjectTypes = $supportedObjectTypes;

		parent::__construct( $name, NULL );
	}

	public static function getTaxonomyRewriteSlug( $taxonomyName )
	{
		$taxonomy = get_taxonomy( $taxonomyName );
		
		if($taxonomy)
		{
			return $taxonomy->rewrite['slug'];
		}

		return NULL;
	}

	/* METHODS / ACTIONS */
	public function addColumn( WPAdminTaxonomyColumn $column )
	{
		$this->_columnsMap[ $column->getName() ] = $column;

		return $this;
	}

	/* SET AND GET */
	public function getSupportedObjectTypes()
	{
		return $this->_supportedObjectTypes;
	}

	public function getArgs()
	{
		return $this->_args;
	}
	public function setArg( $key, $value )
	{
		$this->_args[ $key ] = $value;
		return $this;
	}
	public function getArg( $arg )
	{
		return array_key_exists($arg, $this->_args) ? $this->_args[ $arg ] : NULL;
	}

	/**
	*	These arguments are parsed to the meta box, when calling get_terms, if argument meta_box_cb isn't set or hierarchical is equal to FALSE.
	*/
	public function getMetaBoxTermsArgs()
	{
		return $this->_metaBoxTermsArgs;
	}

	/* WP ACTIONS */
	public function manage_columns( $columns )
	{
		foreach($this->_columnsMap as $wpAdminPostTypeColumn)
		{
			$columns[ $wpAdminPostTypeColumn->getName() ] = $wpAdminPostTypeColumn->getValue();
		}

		return $columns;
	}

	final public function custom_column($out, $columnName, $termID)
	{
		if(array_key_exists($columnName, $this->_columnsMap))
		{
			$this->_columnsMap[ $columnName ]->render( $this->getName(), $termID );
		}
	}
}