<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 15:23
	 */

	namespace tutomvc;

	class TestFormGroup extends ObjectMetaBox
	{
		const NAME          = "form_group";
		const SOME_TEXT     = "some_text";
		const SOME_SELECTOR = "some_selector";

		function __construct()
		{
			parent::__construct( self::NAME, "Test form group", "Just testing this." );
//			$this->setSingle( FALSE );
//			$this->setMin( 1 );
			$this->setMax( 1 );

			$this->addFormElement( new DefaultFormGroup() );

			$this->addFormElement( new FormInput( self::SOME_TEXT, "Some text", "The description", FormInput::TYPE_TEXT, FALSE, "The placeholder" ) )
			     ->setValue( "Hej" );

			$this->addFormElement( new SelectFormInput( self::SOME_SELECTOR, "Some selector", "Select some stuff", FALSE, FALSE, "Placeholder" ) )
			     ->addOption( "Sverige", "sv", "Länder" )
			     ->addOption( "Norge", "no", "Länder" )
			     ->addOption( "Finland", "fi", "Länder" )
			     ->addOption( "Ryssland", "ru", "Länder" )
			     ->addOption( "Danmark", "dk", "Länder" );
		}
	}