<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 13/01/15
	 * Time: 15:23
	 */

	namespace tutomvc;

	class TestFieldGroup extends FormFieldGroup
	{
		const NAME      = "test_section";
		const SOME_TEXT = "some_text";

		function __construct()
		{
			parent::__construct( self::NAME );

			$this->addField( new InputFormField( self::SOME_TEXT, "Some text" ) );
		}
	}