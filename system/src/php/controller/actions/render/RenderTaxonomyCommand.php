<?php
namespace tutomvc;

class RenderTaxonomyCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct( ActionCommand::RENDER_TAXONOMY_FIELD );
		$this->acceptedArguments = 2;
	}

	function execute()
	{
		$post = $this->getArg( 0 );
		$taxonomy = $this->getArg( 1 );
		$tax = get_taxonomy( $taxonomy->getName() );

		// Options
		$options = array();
		foreach(get_terms( $taxonomy->getName(), $taxonomy->getMetaBoxTermsArgs() ) as $term)
		{
			$options[ $term->name ] = $term->name;
		}

		$metaField = new MetaField(
			"tax_input[" . $taxonomy->getName() . "]",
			"",
			"",
			MetaField::TYPE_SELECTOR_MULTIPLE,
			array(
				MetaField::SETTING_OPTIONS => $options,
				MetaField::SETTING_INPUT_ON_ENTER => property_exists( $tax->cap, "create_terms" ) ? current_user_can( $tax->cap->create_terms ) : current_user_can( $tax->cap->manage_terms ),
				MetaField::SETTING_READ_ONLY => !current_user_can( $tax->cap->assign_terms )
			),
			$conditions
		);

		$metaFieldMediator = $this->getFacade()->view->hasMediator( MetaFieldMediator::NAME ) ? $this->getFacade()->view->getMediator( MetaFieldMediator::NAME ) : $this->getFacade()->view->registerMediator( new MetaFieldMediator() );
		$metaFieldMediator->setMetaField( $metaField );
		$metaFieldMediator->parse( "metaVO", new TaxonomyVO( $taxonomy->getName(), $metaField->getName(), $post->ID, $metaField ) );
		$metaFieldMediator->parse( "key", $metaField->getName() );
		$metaFieldMediator->parse( "elementClasses", array( "TaxonomyMetaField" ) );
		$metaFieldMediator->render();
	}
}