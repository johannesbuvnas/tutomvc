<?php
namespace tutons;

class MetaFieldMediator extends SystemMediator
{
	const NAME = "MetaFieldMediator";

	private $_metaField;

	private $_inputMediator;

	function __construct()
	{
		$this->setName( self::NAME );
		$this->setTemplate( "meta/meta-field.php" );
	}

	function onRegister()
	{
		$this->_inputMediator = $this->getFacade()->view->hasMediator( MetaFieldInputMediator::NAME ) ? $this->getFacade()->view->getMediator( MetaFieldInputMediator::NAME ) : $this->getFacade()->view->registerMediator( new MetaFieldInputMediator() );
	}

	function render()
	{
		$this->parse( "metaField", $this->_metaField );

		switch( $this->_metaField->getType() )
		{
			default:

				$this->_inputMediator->setTemplate( "meta/inputs/default.php" );

			break;
		}

		$this->parse( "inputMediator", $this->_inputMediator );

		parent::render();
	}

	/* SET AND GET */
	public function setMetaField( MetaField $metaField )
	{
		$this->_metaField = $metaField;
	}
	public function getMetaField()
	{
		return $this->_metaField;
	}

	private function getTemplateFileReference( $fileReference )
	{
		return Facade::getInstance( Facade::KEY_SYSTEM_FACADE )->getTemplateFileReference( fileReference );
	}
}