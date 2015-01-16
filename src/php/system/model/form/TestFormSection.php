<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 15:23
	 */

	namespace tutomvc;

	class TestFormGroup extends FormGroup
	{
		const NAME          = "test_section";
		const SOME_TEXT     = "some_text";
		const SOME_SELECTOR = "some_selector";

		function __construct()
		{
			parent::__construct( self::NAME, "Test form group", "Just testing this." );

			$this->addInput( new FormInput( self::SOME_TEXT, "Some text", "The description", FormInput::TYPE_TEXT, FALSE, "The placeholder" ) )
			     ->setValue( "Hej" );

			$this->addInput( new SelectFormInput( self::SOME_SELECTOR, "Some selector", "Select some stuff", SelectFormInput::TYPE_MULTIPLE, FALSE, "Placeholder" ) )
			     ->addOption( "Sverige", "sv", "Länder" )
			     ->addOption( "Norge", "no", "Länder" )
			     ->addOption( "Finland", "fi", "Länder" )
			     ->addOption( "Ryssland", "ru", "Länder" )
			     ->addOption( "Danmark", "dk", "Länder" );
		}
	}