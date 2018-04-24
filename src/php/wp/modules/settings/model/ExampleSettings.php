<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 21/05/15
	 * Time: 09:36
	 */

	namespace tutomvc\wp\settings;

	use tutomvc\examples\form\groups\ExampleFormGroup;
	use tutomvc\examples\form\groups\ExampleSelectorFormGroup;
	use tutomvc\wp\form\inputs\WPEditorFormInput;

	class ExampleSettings extends Settings
	{
		const NAME      = "example_settings";
		const WP_EDITOR = "wp_editor";

		function __construct()
		{
			parent::__construct( self::NAME, "general", "Example settings", "A bunch of example settings." );

			$this->add( new ExampleFormGroup() );
			$this->add( new ExampleSelectorFormGroup() );
			/** @var WPEditorFormInput $wpEditor */
			$wpEditor = $this->add( new WPEditorFormInput( self::WP_EDITOR, "Some WP Editor", "Edit some stuff", FALSE, FALSE, "Placeholder" ) )
			                 ->setDefaultValue( '<p class="VA">hej</p>' );
		}
	}