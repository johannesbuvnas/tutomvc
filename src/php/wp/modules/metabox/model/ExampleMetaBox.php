<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/04/15
	 * Time: 15:29
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\examples\form\groups\ExampleFormGroup;
	use tutomvc\examples\form\groups\ExampleSelectorFormGroup;
	use tutomvc\wp\form\inputs\WPEditorFormInput;

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