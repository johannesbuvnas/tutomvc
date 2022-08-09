<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 21/05/15
	 * Time: 09:36
	 */

	namespace TutoMVC\Example\WordPress;

	use TutoMVC\Example\Form\Group\ExampleFormGroup;
	use TutoMVC\Example\Form\Input\ExampleSelectorFormGroup;
	use TutoMVC\WordPress\Form\Input\WPEditorFormInput;
	use TutoMVC\WordPress\Modules\Settings\Controller\Settings;

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
			$wpEditor = $this->add( new WPEditorFormInput( self::WP_EDITOR, "Some WP Editor", "Edit some stuff" ) )
			                 ->setDefaultValue( '<p class="VA">hej</p>' );
		}
	}
