<?php
	namespace tutomvc\wp;

	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\system\controller\actions\AdminEnqueueScriptsAction;

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
			$this->registerCommand( "admin_enqueue_scripts", new AdminEnqueueScriptsAction() );
		}
	}
