<?php
	namespace tutomvc;

	use tutomvc\wp\metabox\ExampleMetaBox;
	use tutomvc\wp\metabox\MetaBoxModule;
	use tutomvc\wp\metabox\MetaBoxModuleFacade;
	use tutomvc\wp\notification\NotificationModule;
	use tutomvc\wp\setting\ExampleSetting;
	use tutomvc\wp\setting\SettingModule;
	use tutomvc\wp\taxonomy\ExampleTaxonomy;
	use tutomvc\wp\taxonomy\TaxonomyModule;

	/**
	 * Class SystemFacade
	 * @package tutomvc
	 * @property PostTypeProxy postTypeCenter
	 * @property MetaBoxProxy metaCenter
	 * @property UserMetaProxy userMetaCenter
	 * @property AdminMenuPageProxy adminMenuPageCenter
	 * @property ImageSizeProxy imageSizeCenter
	 * @property SettingsProxy settingsCenter
	 * @property LogProxy logCenter
	 * @property NotificationProxy notificationCenter
	 * @property TaxonomyProxy taxonomyCenter
	 * @property UserColumnProxy userColumnCenter
	 */
	final class SystemFacade extends Facade
	{
		/* CONSTANTS */
		const DEVELOPMENT_MODE = TRUE;
		const LOGS_DIRECTORY   = "/logs/";

		const STYLE_CSS         = "tutomvc-css";
		const SCRIPT_JS         = "tutomvc-js";
		const SCRIPT_JS_REQUIRE = "tutomvc-require-js";

		const SESSION_NAME = "com.tutomvc.system.session";

		public static $PRODUCTION_MODE = FALSE;

		/* PUBLIC VARS */
		public $postTypeCenter;
		public $metaCenter;
		public $userMetaCenter;
		public $menuCenter;
		public $adminMenuPageCenter;
		public $imageSizeCenter;
		public $settingsCenter;
		public $logCenter;
		public $notificationCenter;
		public $taxonomyCenter;
		public $userColumnCenter;
		public $repository;
		public $log;

		function __construct()
		{
			parent::__construct( Facade::KEY_SYSTEM );
		}

		public function onRegister()
		{
			if ( !session_id() )
			{
				session_name( SystemFacade::SESSION_NAME );
				session_start();
			}

//			$this->repository = new GitRepositoryVO( TutoMVC::getRoot(), TutoMVC::GIT_REPOSITORY_URL, TutoMVC::GIT_REPOSITORY_BRANCH );
			MetaBoxModule::add( new ExampleMetaBox() );
			SettingModule::add( new ExampleSetting() );
			TaxonomyModule::add( new ExampleTaxonomy() );
			NotificationModule::add( "Yoyoyo!", NotificationModule::TYPE_UPDATE_NAG );

			return;
			$this->prepModel();
			$this->prepView();
			$this->prepController();
		}

		private function prepModel()
		{
			$this->notificationCenter = $this->model->registerProxy( new NotificationProxy() );
			$this->logCenter          = $this->model->registerProxy( new LogProxy() );
			$this->postTypeCenter     = $this->model->registerProxy( new PostTypeProxy() );
//			$this->metaCenter          = $this->model->registerProxy( new MetaBoxProxy() );
//			$this->taxonomyCenter      = $this->model->registerProxy( new TaxonomyProxy() );
			$this->userColumnCenter = $this->model->registerProxy( new UserColumnProxy() );
			$this->userMetaCenter   = $this->model->registerProxy( new UserMetaProxy() );
//			$this->menuCenter          = $this->model->registerProxy( new MenuProxy() );
//			$this->adminMenuPageCenter = $this->model->registerProxy( new AdminMenuPageProxy() );
			$this->imageSizeCenter = $this->model->registerProxy( new ImageSizeProxy() );
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
			$this->controller->registerCommand( new InitCommand() );
			$this->controller->registerCommand( new AdminInitCommand() );
			$this->controller->registerCommand( new ExceptionCommand() );
		}

		/* ACTIONS */
		public function log( $message )
		{
			return $this->logCenter->add( $message );
		}
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////
