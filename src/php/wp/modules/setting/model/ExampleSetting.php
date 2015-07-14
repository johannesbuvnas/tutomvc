<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 21/05/15
	 * Time: 09:36
	 */

	namespace tutomvc\wp\setting;

	use tutomvc\ExampleFormGroup;
	use tutomvc\ExampleSelectorFormGroup;
	use tutomvc\WPEditorFormInput;

	class ExampleSetting extends Setting
	{
		const NAME      = "example_settings";
		const WP_EDITOR = "wp_editor";

		function __construct()
		{
			parent::__construct( self::NAME, "general", "Example settings", "A bunch of example settings." );

			$this->addFormElement( new ExampleFormGroup() );
			$this->addFormElement( new ExampleSelectorFormGroup() );
			/** @var WPEditorFormInput $wpEditor */
			$wpEditor = $this->addFormElement( new WPEditorFormInput( self::WP_EDITOR, "Some WP Editor", "Edit some stuff", FALSE, FALSE, "Placeholder" ) )
			                 ->setDefaultValue( '<p class="VA">hej</p>' );
		}
	}