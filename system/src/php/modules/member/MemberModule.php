<?php
namespace tutomvc\modules\member;
use \tutomvc\Facade;

class MemberModule extends Facade
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
}