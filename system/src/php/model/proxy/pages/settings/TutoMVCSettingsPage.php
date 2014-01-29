<?php
namespace tutomvc;

class TutoMVCSettingsPage extends AdminMenuPage
{
	const NAME = "tutomvc";


	function __construct()
	{
		parent::__construct( __( "Tuto MVC" ), __( "Tuto MVC" ), "manage_options", self::NAME );
		//$this->setType( AdminMenuPage::TYPE_OPTIONS );
		$this->addSubpage( new TutoMVCLogsPage() );
	}
}