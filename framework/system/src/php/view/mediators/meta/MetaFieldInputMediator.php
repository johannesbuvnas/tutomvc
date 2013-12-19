<?php
namespace tutons;

class MetaFieldInputMediator extends SystemMediator
{
	const NAME = __CLASS__;
	
	function __construct()
	{
		parent::__construct();

		$this->setTemplate( NULL );
	}
}