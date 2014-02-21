<?php
namespace tutomvc;

class SettingsProxy extends Proxy
{
	const NAME = __CLASS__;
	const WP_HOOK_REGISTER = "admin_init";


	public function onRegister()
	{
		// Controller
		add_action( self::WP_HOOK_REGISTER, array( $this, "register" ) );
		$this->getFacade()->controller->registerCommand( new RenderSettingsFieldCommand() );
	}

	/* ACTIONS */
	public function add( $item, $key = NULL )
	{
		foreach($item->getFields() as $sectionField)
		{
			add_option( $sectionField->getName(), apply_filters( FilterCommand::META_VALUE, NULL, NULL, $sectionField ) );
			$this->getFacade()->controller->registerCommand( new GetOptionFilterCommand( $sectionField->getName() ) );
		}
		return parent::add( $item, $key );
	}

	public function register()
	{
		foreach($this->getMap() as $item) $this->registerItem( $item );
	}

	protected function registerItem( Settings $item )
	{
		if($item->getRegisterAsNewSection()) add_settings_section( $item->getName(), $item->getTitle(), array( $item, "renderDescription" ), $item->getMenuSlug() );

		foreach($item->getFields() as $sectionField)
		{
			add_settings_field( $sectionField->getName(), $sectionField->getTitle(), array( $this, "renderSectionField" ), $item->getMenuSlug(), $item->getName(), array( "field" => $sectionField ) );
			register_setting( $item->getName(), $sectionField->getName() );
		}
	}
	
	public function renderSectionField( $args )
	{
		do_action( ActionCommand::PREPARE_META_FIELD );

		do_action( ActionCommand::RENDER_SETTINGS_FIELD, $args['field'] );
	}

	/* METHODS */
	public function getSectionFieldByOptionName( $optionName )
	{
		foreach($this->getMap() as $settings)
		{
			$field = $settings->getField( $optionName );
			if($field) return $field;
		}

		return NULL;
	}
	/* EVENTS */

}