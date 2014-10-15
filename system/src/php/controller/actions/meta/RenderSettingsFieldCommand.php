<?php
namespace tutomvc;

class RenderSettingsFieldCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct( ActionCommand::RENDER_SETTINGS_FIELD );
	}

	function execute()
	{
		$field = $this->getArg(0);

		if(!$field->getSetting( MetaField::SETTING_RENDERED ))
		{
			$metaFieldMediator = $this->getFacade()->view->hasMediator( MetaFieldMediator::NAME ) ? $this->getFacade()->view->getMediator( MetaFieldMediator::NAME ) : $this->getFacade()->view->registerMediator( new MetaFieldMediator() );
			$metaFieldMediator->setMetaField( $field );
			$metaFieldMediator->parse( "metaVO", new OptionVO( $field ) );
			$metaFieldMediator->parse( "key", $field->getName() );
			$metaFieldMediator->parse( "elementClasses", array( "SettingsField" ) );
			$metaFieldMediator->render();

			$field->setSetting( MetaField::SETTING_RENDERED, TRUE );
		}
	}
}