<?php
	namespace tutomvc\wp\taxonomy;

	class TaxonomyModuleFacade extends \tutomvc\Facade
	{
		const KEY = "tutomvc/modules/taxonomy";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		function onRegister()
		{
			// Model
			$this->model->registerProxy( new TaxonomyProxy() );
		}

		/**
		 * @return TaxonomyProxy
		 */
		function getProxy()
		{
			return $this->model->getProxy( TaxonomyProxy::NAME );
		}
	}