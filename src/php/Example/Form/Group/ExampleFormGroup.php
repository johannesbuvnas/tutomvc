<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/01/15
	 * Time: 17:30
	 */

	namespace TutoMVC\Example\Form\Group;

	use TutoMVC\Form\Group\FormGroup;
	use TutoMVC\Form\Input\CheckBoxFormInput;
	use TutoMVC\Form\Input\FormInput;

	class ExampleFormGroup extends FormGroup
	{
		const NAME               = "my_form_group";
		const INPUT_TEXT         = "text";
		const GROUP_EMAIL        = "email";
		const GROUP_EMAIL_NAME   = "email_name";
		const GROUP_EMAIL_DOMAIN = "email_domain";
		const GROUP_EMAIL_TLD    = "email_tld";

		function __construct()
		{
			parent::__construct( self::NAME, "My form group", "Here I could explain this a bit more." );

			// Just a normal and fancy text-input
			$this->add( new FormInput( self::INPUT_TEXT, "Default text", "Type something fancy! This field won't be validated." ) );

			$this->add( new CheckBoxFormInput( "checkbox", "Check me" ) );
		}
	}
