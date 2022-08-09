<?php
	namespace TutoMVC\WordPress\Modules\Taxonomy;

	use TutoMVC\WordPress\Core\Facade\Facade;
	use TutoMVC\WordPress\Modules\Taxonomy\Model\TaxonomyProxy;

	class TaxonomyModuleFacade extends Facade
	{
		const KEY = "com.tutomvc.wp.modules.taxonomy";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		function onRegister()
		{
			parent::onRegister();
		}

		function prepModel()
		{
			$this->registerProxy( new TaxonomyProxy() );
		}
	}
