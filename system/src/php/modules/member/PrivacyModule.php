<?php namespace tutomvc\modules\privacy;
use \tutomvc\Facade;

class PrivacyModule extends Facade
{
	const KEY = "tutomvc/modules/member/facade";

	function __construct()
	{
		parent::__construct( self::KEY );
	}

	function onRegister()
	{
		$this->vo = $this->getSystem()->vo;
		$this->controller->registerCommand( new InitCommand() );
	}

	public static function getInstance()
	{
		if(Facade::getInstance( self::KEY )) return Facade::getInstance( self::KEY );

		$module = new PrivacyModule();
		$systemFacade = Facade::getInstance( Facade::KEY_SYSTEM );
		return $systemFacade->registerSubFacade( $module );
	}
}