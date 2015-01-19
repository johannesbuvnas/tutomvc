<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 15:23
	 */

	namespace tutomvc;

	class TestFormGroup extends PostMetaBox
	{
		const INPUT_GROUP        = "emails";
		const INPUT_GROUP_DOMAIN = "domain";
		const INPUT_GROUP_NAME   = "name";
		const NAME               = "form_group";
		const SOME_TEXT          = "some_text";
		const SOME_SELECTOR      = "some_selector";
		const WP_EDITOR      = "wp_editor";

		function __construct()
		{
			parent::__construct( self::NAME, "Test form group", "Just testing this.", 1, -1 );

			$this->addFormElement( new DefaultFormGroup() );

			$this->addFormElement( new FormInput( self::SOME_TEXT, "Some text", "The description", FormInput::TYPE_TEXT, FALSE, "The placeholder" ) )
			     ->setValue( "Hej" );

			$this->addFormElement( new SelectFormInput( self::SOME_SELECTOR, "Some selector", "Select some stuff", FALSE, FALSE, "Placeholder" ) )
			     ->addOption( "Sverige", "sv", "Länder" )
			     ->addOption( "Norge", "no", "Länder" )
			     ->addOption( "Finland", "fi", "Länder" )
			     ->addOption( "Ryssland", "ru", "Länder" )
			     ->addOption( "Danmark", "dk", "Länder" );

			/** @var FormInputGroup $inputGroup */
			$inputGroup = $this->addFormElement( new FormInputGroup( self::INPUT_GROUP, "Input group", "Default input groups" ) );
			$inputGroup->addFormElement( new FormInput( self::INPUT_GROUP_NAME, NULL ) )
			           ->setPlaceholder( "Name" );
			$inputGroup->addFormElement( new FormInputAddon( "@" ) );
			$inputGroup->addFormElement( new FormInput( self::INPUT_GROUP_DOMAIN, NULL ) )
			           ->setPlaceholder( "Domain" );

			$this->addFormElement( new WPEditorFormInput( self::WP_EDITOR, "Some WP Editor", "Edit some stuff", FALSE, FALSE, "Placeholder" ) )
				->setDefaultValue('<p class="VA">hej</p>');
		}
	}