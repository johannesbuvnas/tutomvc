<?php
	namespace tutomvc;

	use tutomvc\wp\log\LogModule;
	use tutomvc\wp\metabox\ExampleMetaBox;
	use tutomvc\wp\metabox\MetaBoxModule;
	use tutomvc\wp\metabox\MetaBoxModuleFacade;
	use tutomvc\wp\notification\NotificationModule;
	use tutomvc\wp\setting\ExampleSettings;
	use tutomvc\wp\setting\SettingsModule;
	use tutomvc\wp\taxonomy\ExampleTaxonomy;
	use tutomvc\wp\taxonomy\TaxonomyModule;
	use Whoops\Exception\ErrorException;

	/**
	 * Class SystemAppFacade
	 * @package tutomvc
	 */
	final class SystemAppFacade extends Facade
	{
		function __construct()
		{
			parent::__construct( TutoMVC::NAME );
		}

		public function onRegister()
		{
			if ( !session_id() )
			{
				session_name( TutoMVC::NAME );
				session_start();
			}

			$this->setRoot( TutoMVC::getRoot() );
			$this->setURL( TutoMVC::getURL() );

			parent::onRegister();
		}

		protected function prepModel()
		{
//			MetaBoxModule::add( new ExampleMetaBox() );
//			SettingsModule::add( new ExampleSettings() );
//			TaxonomyModule::add( new ExampleTaxonomy() );
//			NotificationModule::add( $this->getURL(), NotificationModule::TYPE_UPDATE );
//			LogModule::add( "YOYYOO" );
		}

		protected function prepController()
		{

		}
	}
