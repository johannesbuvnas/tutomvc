<?php
namespace tutomvc;

class AdminMenuPageMediator extends Mediator
{
	protected $_adminMenuPage;


	/* SET AND GET */
	function setAdminMenuPage( AdminMenuPage $adminMenuPage )
	{
		$this->_adminMenuPage = $adminMenuPage;
	}
	function getAdminMenuPage()
	{
		return $this->_adminMenuPage;
	}
}