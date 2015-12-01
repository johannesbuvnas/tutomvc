<?php
	namespace tutomvc;

	use tutomvc\wp\log\LogModule;
	use tutomvc\wp\metabox\ExampleMetaBox;
	use tutomvc\wp\metabox\MetaBoxModule;
	use tutomvc\wp\metabox\MetaBoxModuleFacade;
	use tutomvc\wp\notification\NotificationModule;
	use tutomvc\wp\setting\ExampleSetting;
	use tutomvc\wp\setting\SettingModule;
	use tutomvc\wp\taxonomy\ExampleTaxonomy;
	use tutomvc\wp\taxonomy\TaxonomyModule;
	use Whoops\Exception\ErrorException;

	/**
	 * Class SystemAppFacade
	 * @package tutomvc
	 */
	final class SystemAppFacade extends Facade
	{
		/* CONSTANTS */
		/* PUBLIC VARS */
		public $postTypeCenter;
		public $userMetaCenter;
		public $imageSizeCenter;
		public $userColumnCenter;

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

			MetaBoxModule::add( new ExampleMetaBox() );
			SettingModule::add( new ExampleSetting() );
			TaxonomyModule::add( new ExampleTaxonomy() );
			NotificationModule::add( $this->getURL(), NotificationModule::TYPE_UPDATE );
			LogModule::add( "YOYYOO" );

			return;
			$this->prepModel();
			$this->prepView();
			$this->prepController();
		}

		private function prepModel()
		{
//			$this->notificationCenter = $this->model->registerProxy( new NotificationProxy() );
//			$this->logCenter      = $this->model->registerProxy( new LogProxy() );
			$this->postTypeCenter = $this->registerProxy( new PostTypeProxy() );
//			$this->metaCenter          = $this->model->registerProxy( new MetaBoxProxy() );
//			$this->taxonomyCenter      = $this->model->registerProxy( new TaxonomyProxy() );
			$this->userColumnCenter = $this->registerProxy( new UserColumnProxy() );
			$this->userMetaCenter   = $this->registerProxy( new UserMetaProxy() );
//			$this->menuCenter          = $this->model->registerProxy( new MenuProxy() );
//			$this->adminMenuPageCenter = $this->model->registerProxy( new AdminMenuPageProxy() );
			$this->imageSizeCenter = $this->registerProxy( new ImageSizeProxy() );
//			$this->settingsCenter      = $this->model->registerProxy( new SettingsProxy() );

			if ( self::DEVELOPMENT_MODE )
			{
				$this->notificationCenter->add( "<strong>TutoMVC</strong> <code>DEVELOPMENT_MODE: ON</code>", "error" );
			}
		}

		private function prepView()
		{
			$this->view->registerMediator( new JSGlobalMediator() );
		}

		private function prepController()
		{
			$this->registerCommand( new InitCommand() );
			$this->registerCommand( new AdminInitCommand() );
			$this->registerCommand( new ExceptionCommand() );
		}

		/* ACTIONS */
		public function log( $message )
		{
			return $this->logCenter->add( $message );
		}
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
