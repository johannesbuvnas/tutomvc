<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 15:23
	 */

	namespace tutomvc;

	class TestGroup extends FormGroup
	{
		const NAME      = "test_section";
		const SOME_TEXT = "some_text";

		function __construct()
		{
			parent::__construct( self::NAME );

			$this->addInput( new FormInput( self::SOME_TEXT, "Some text", "The description", FormInput::TYPE_TEXT, FALSE, "The placeholder" ) )
			     ->setValue( "Hej" );
		}
	}