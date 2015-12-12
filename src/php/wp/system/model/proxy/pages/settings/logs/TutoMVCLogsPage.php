<?php
namespace tutomvc\wp;

class TutoMVCLogsPage extends AdminMenuPage
{
	const NAME = "tutomvc/logs";


	function __construct()
	{
		parent::__construct( __( "Logs" ), __( "Logs" ), "manage_options", self::NAME );
	}

	function getContentMediatorName()
	{
		return TutoMVCLogsPageContentMediator::NAME;
	}
}