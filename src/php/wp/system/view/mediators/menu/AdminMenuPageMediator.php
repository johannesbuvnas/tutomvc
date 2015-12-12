<?php
namespace tutomvc\wp;

class AdminMenuPageMediator extends Mediator
{

	/* SET AND GET */
	function setAdminMenuPage( AdminMenuPage $adminMenuPage )
	{
		$this->parse( "adminMenuPage", $adminMenuPage );

		return $this;
	}
	function getAdminMenuPage()
	{
		return $this->retrieve( "adminMenuPage" );
	}
}