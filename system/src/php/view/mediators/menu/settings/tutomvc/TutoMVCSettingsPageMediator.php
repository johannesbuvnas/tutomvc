<?php
namespace tutomvc;

class TutoMVCSettingsPageMediator extends AdminMenuPageMediator
{
	const NAME = "menu/settings/tutomvc/wrapper.php";

	function __construct()
	{
		parent::__construct( self::NAME );
	}

	public function onRegister()
	{
		$this->getFacade()->view->registerMediator( new TutoMVCLogsPageContentMediator() );
	}

	function getContent()
	{
		$this->parse( "currentPage", $this->getAdminMenuPage() );
		$this->parse( "contentMediator", $this->getFacade()->view->getMediator( $this->getAdminMenuPage()->getContentMediatorName() ) );

		return parent::getContent();
	}
}
