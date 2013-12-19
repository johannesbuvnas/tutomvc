<?php
namespace tutomvc;
////////////////////////////////////////////////////////////////////////////////////////////////////////////
final class SystemFacade extends Facade
{
	/* CONSTANTS */
	const VERSION = "1.0";

	public static $PRODUCTION_MODE = false;

	/* PUBLIC VARS */
	public $postTypeCenter;
	public $metaCenter;
	public $menuCenter;
	public $imageSizeCenter;

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
		$this->imageSizeCenter = $this->model->registerProxy( new ImageSizeProxy() );
	}

	private function prepView()
	{

	}

	private function prepController()
	{
		$this->controller->registerCommand( new AdminInitCommand() );
	}
}
////////////////////////////////////////////////////////////////////////////////////////////////////////////