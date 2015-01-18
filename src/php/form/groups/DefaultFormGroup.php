<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/01/15
	 * Time: 17:30
	 */

	namespace tutomvc;

	class DefaultFormGroup extends FormGroup
	{
		const NAME = "default";
		const TEXT = "text";

		function __construct()
		{
			parent::__construct( self::NAME, "Default form group", "Some default form group elements" );

			$this->addFormElement( new FormInput( self::TEXT, "Default text", "Some default text" ) );
		}
	}