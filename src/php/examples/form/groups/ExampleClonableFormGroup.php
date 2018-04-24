<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 15:23
	 */

	namespace tutomvc\examples\form\groups;

	use tutomvc\core\form\groups\FissileFormGroup;
	use tutomvc\wp\form\inputs\WPEditorFormInput;

	class ExampleFissileFormGroup extends FissileFormGroup
	{
		const INPUT_GROUP        = "emails";
		const INPUT_GROUP_DOMAIN = "domain";
		const INPUT_GROUP_NAME   = "name";
		const NAME               = "form_gr()oup";
		const SOME_TEXT          = "some_text";
		const SOME_SELECTOR      = "some_selector";
		const WP_EDITOR          = "wp_editor";

		function __construct()
		{
			parent::__construct( self::NAME, "Example of clonable form group", "What the title says.", 1, - 1 );

			$this->add( new ExampleFormGroup() );
			$this->add( new ExampleSelectorFormGroup() );
			$this->add( new WPEditorFormInput( self::WP_EDITOR, "Some WP Editor", "Edit some stuff", FALSE, FALSE, "Placeholder" ) )
			     ->setDefaultValue( '<p class="VA">hej</p>' );
		}
	}