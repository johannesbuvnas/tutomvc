<?php
	namespace tutomvc\wp\taxonomy;

	class TaxonomyModuleFacade extends \tutomvc\Facade
	{
		const KEY = "com.tutomvc.wp.modules.taxonomy";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		function onRegister()
		{
			// Model
			$this->registerProxy( new TaxonomyProxy() );
		}
	}