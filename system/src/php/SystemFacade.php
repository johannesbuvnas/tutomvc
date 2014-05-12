<?php
namespace tutomvc;
////////////////////////////////////////////////////////////////////////////////////////////////////////////
final class SystemFacade extends Facade
{
	/* CONSTANTS */
	const DEVELOPMENT_MODE = TRUE;
	const LOGS_DIRECTORY = "/logs/";

	const STYLE_CSS = "tutomvc-css";
	const SCRIPT_JS = "tutomvc-js";
	const SCRIPT_JS_REQUIRE = "tutomvc-require-js";

	public static $PRODUCTION_MODE = false;

	/* PUBLIC VARS */
	public $postTypeCenter;
	public $metaCenter;
	public $menuCenter;
	public $adminMenuPageCenter;
	public $imageSizeCenter;
	public $settingsCenter;
	public $logCenter;
	public $notificationCenter;
	public $repository;

	function __construct()
	{
		parent::__construct( Facade::KEY_SYSTEM );
	}

	public function onRegister()
	{
		$this->prepModel();
		$this->prepView();
		$this->prepController();
	}

	private function prepModel()
	{
		$this->notificationCenter = $this->model->registerProxy( new NotificationProxy() );
		$this->postTypeCenter = $this->model->registerProxy( new PostTypeProxy() );
		$this->metaCenter = $this->model->registerProxy( new MetaBoxProxy() );
		$this->menuCenter = $this->model->registerProxy( new MenuProxy() );
		$this->adminMenuPageCenter = $this->model->registerProxy( new AdminMenuPageProxy() );
		$this->imageSizeCenter = $this->model->registerProxy( new ImageSizeProxy() );
		$this->settingsCenter = $this->model->registerProxy( new SettingsProxy() );
		$this->logCenter = $this->model->registerProxy( new LogProxy() );

		if(self::DEVELOPMENT_MODE) $this->notificationCenter->add( "TutoMVC System: DEVELOPMENT MODE" );
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

	/* SET AND GET */
	public function getLogsPath()
	{
		return FileUtil::filterFileReference( $this->getVO()->getRoot().SystemFacade::LOGS_DIRECTORY );
	}
}
///////////////////////////////////////////////////////////////////////////////////////////////////////////
