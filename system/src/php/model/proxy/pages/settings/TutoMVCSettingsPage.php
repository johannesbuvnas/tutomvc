<?php
namespace tutomvc;

class TutoMVCSettingsPage extends AdminMenuPage
{
	const NAME = "tutomvc";


	function __construct( $mediator )
	{
		parent::__construct( __( "Tuto MVC" ), __( "Tuto MVC" ), "manage_options", self::NAME );
		//$this->setType( AdminMenuPage::TYPE_OPTIONS );
		$this->setMediator( $mediator );


		$this->addSubpage( new TutoMVCGitPage() )
		->setMediator( $mediator );

		$this->addSubpage( new TutoMVCLogsPage() )
			->setMediator( $mediator );
	}

	function getContentMediatorName()
	{
		return '';
	}
}
