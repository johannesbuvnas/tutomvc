<?php
	namespace tutomvc\wp\taxonomy;

	use tutomvc\wp\TutoMVC;

	class TaxonomyModuleFacade extends \tutomvc\wp\Facade
	{
		const KEY = "com.tutomvc.wp.modules.taxonomy";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		function onRegister()
		{
			parent::onRegister();
			if ( is_admin() )
			{
				wp_enqueue_style( "select2", $this->getURL( "bower_components/select2/dist/css/select2.min.css" ), NULL, TutoMVC::VERSION );

				wp_enqueue_script( "jquery-ui-sortable" );
				wp_enqueue_script( "select2", $this->getURL( "bower_components/select2/dist/js/select2.full.min.js" ), NULL, TutoMVC::VERSION );
			}
		}

		function prepModel()
		{
			$this->registerProxy( new TaxonomyProxy() );
		}
	}