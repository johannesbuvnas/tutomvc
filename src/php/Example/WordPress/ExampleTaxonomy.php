<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 24/08/15
	 * Time: 16:24
	 */

	namespace TutoMVC\Example\WordPress;

	use TutoMVC\WordPress\Modules\Taxonomy\Controller\Taxonomy;
	use TutoMVC\WordPress\Modules\Taxonomy\Controller\WPAdminTaxonomyColumn;

	class ExampleTaxonomy extends Taxonomy
	{
		const NAME = "countries";

		function __construct()
		{
			parent::__construct( self::NAME, "post", array(
				'hierarchical'      => FALSE,
				'show_ui'           => TRUE,
				'show_admin_column' => TRUE,
				'query_var'         => TRUE,
				'rewrite'           => FALSE,
				'labels'            => array(),
			) );

			$this->addColumn( new WPAdminTaxonomyColumn( "something", "This is a column" ) );
		}
	}
