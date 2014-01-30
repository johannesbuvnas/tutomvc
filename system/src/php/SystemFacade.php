<?php
namespace tutomvc;
////////////////////////////////////////////////////////////////////////////////////////////////////////////
final class SystemFacade extends Facade
{
	/* CONSTANTS */
	const VERSION = "1.0";
	const LOGS_DIRECTORY = "/logs/";

	public static $PRODUCTION_MODE = false;

	/* PUBLIC VARS */
	public $postTypeCenter;
	public $metaCenter;
	public $menuCenter;
	public $adminMenuPageCenter;
	public $imageSizeCenter;
	public $settingsCenter;
	public $logCenter;

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
		$this->postTypeCenter = $this->model->registerProxy( new PostTypeProxy() );
		$this->metaCenter = $this->model->registerProxy( new MetaBoxProxy() );
		$this->menuCenter = $this->model->registerProxy( new MenuProxy() );
		$this->adminMenuPageCenter = $this->model->registerProxy( new AdminMenuPageProxy() );
		$this->imageSizeCenter = $this->model->registerProxy( new ImageSizeProxy() );
		$this->settingsCenter = $this->model->registerProxy( new SettingsProxy() );
		$this->logCenter = $this->model->registerProxy( new LogProxy() );
	}

	private function prepView()
	{
	}

	private function prepController()
	{
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