<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 15:23
	 */

	namespace tutomvc;

	class ExampleClonableFormGroup extends ClonableFormGroup
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
			parent::__construct( self::NAME, "Test form group", "Just testing this.", 0, - 1 );

			$this->addFormElement( new ExampleFormGroup() );
			$this->addFormElement( new ExampleSelectorFormGroup() );
			$this->addFormElement( new WPEditorFormInput( self::WP_EDITOR, "Some WP Editor", "Edit some stuff", FALSE, FALSE, "Placeholder" ) )
			     ->setDefaultValue( '<p class="VA">hej</p>' );
		}
	}