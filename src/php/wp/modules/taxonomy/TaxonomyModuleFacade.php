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
		}

		function prepModel()
		{
			$this->registerProxy( new TaxonomyProxy() );
		}
	}