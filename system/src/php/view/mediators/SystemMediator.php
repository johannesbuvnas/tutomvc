<?php
namespace tutomvc;

class SystemMediator extends Mediator
{
	public function getTemplate()
	{
		return Facade::getInstance( Facade::KEY_SYSTEM )->getTemplateFileReference( $this->_template );
	}
}