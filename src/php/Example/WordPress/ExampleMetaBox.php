<?php
	namespace TutoMVC\Example\WordPress;

	use TutoMVC\Example\Form\Group\ExampleFormGroup;
	use TutoMVC\Example\Form\Input\ExampleSelectorFormGroup;
	use TutoMVC\WordPress\Form\Input\WPEditorFormInput;
	use TutoMVC\WordPress\Modules\MetaBox\Controller\MetaBox;

	class ExampleMetaBox extends MetaBox
	{
		const NAME      = "example";
		const WP_EDITOR = "wp_editor";

		function __construct()
		{
			parent::__construct( self::NAME, "Example of clonable form group", "What the title says.", array(
				"post",
				"page"
			), 1, - 1 );

			$this->add( new ExampleFormGroup() );
			$this->add( new ExampleSelectorFormGroup() );
			$wpEditor = $this->add( new WPEditorFormInput( self::WP_EDITOR, "Some WP Editor", "Edit some stuff", FALSE, FALSE, "Placeholder" ) )
			                 ->setDefaultValue( '<p class="VA">hej</p>' );
		}
	}
