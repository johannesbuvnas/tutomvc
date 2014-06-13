<?php
namespace tutomvc;

class TaxonomyProxy extends Proxy
{
	const NAME = __CLASS__;

	public function onRegister()
	{
		/* ACTIONS */
		$this->getFacade()->controller->registerCommand( new RenderTaxonomyCommand() );
	}

	public function add( $item, $key = NULL )
	{
		parent::add( $item, $item->getName() );
		$this->register( $item );

		return $this;
	}

	public function register( Taxonomy $taxonomy )
	{
		if( ( is_null( $taxonomy->getArg( "hierarchical" ) ) || $taxonomy->getArg( "hierarchical" ) == FALSE ) && is_null( $taxonomy->getArg( "meta_box_cb" ) ) )
		{
			$taxonomy->setArg( "meta_box_cb", array( $this, "_render" ) );
		}
		add_filter( "manage_edit-" . $taxonomy->getName() . "_columns", array( $taxonomy, "manage_columns" ) );
		// add_filter( "manage_edit-" . $taxonomy->getName() . "_sortable_columns", array( $taxonomy, "manage_sortable_columns" ) );
		add_action( "manage_" . $taxonomy->getName() . "_custom_column", array( $taxonomy, "custom_column" ), 1, 3 );
		register_taxonomy( $taxonomy->getName(), $taxonomy->getSupportedObjectTypes(), $taxonomy->getArgs() );
	}

	public function render( $post, $taxonomyName )
	{
		do_action( ActionCommand::RENDER_TAXONOMY_FIELD, $post, $this->get( $taxonomyName ) );
	}
	public function _render( $post, $params )
	{
		$this->render( $post, $params['args']['taxonomy'] );
	}
}