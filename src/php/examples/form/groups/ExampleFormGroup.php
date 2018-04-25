<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/01/15
	 * Time: 17:30
	 */

	namespace tutomvc\examples\form\groups;

	use tutomvc\core\form\groups\FormGroup;
	use tutomvc\core\form\groups\FormInputGroup;
	use tutomvc\core\form\inputs\FormInput;
	use tutomvc\core\form\inputs\FormInputAddon;

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

			/** @var FormInputGroup $inputGroup */
			$inputGroup = $this->add( new FormInputGroup( self::GROUP_EMAIL, "Email", "Must be a valid email address." ) );
			// Set a method that checks that the inputted email is valid
			$inputGroup->setValidationMethod( array($this, "validateEmailFormGroup") );

			// Adding email part input
			$inputGroup->add( new FormInput( self::GROUP_EMAIL_NAME, NULL ) )
			           ->setPlaceholder( "Name" );

			// Adding @
			$inputGroup->add( new FormInputAddon( "@" ) );

			// Adding email domain input
			$domain = $inputGroup->add( new FormInput( self::GROUP_EMAIL_DOMAIN, NULL ) );
			$domain->setPlaceholder( "Domain" );
			$domain->setDefaultValue( "gmail" );

			// Adding .
			$inputGroup->add( new FormInputAddon( "." ) );

			// Adding top level domain input
			$tld = $inputGroup->add( new FormInput( self::GROUP_EMAIL_TLD, NULL ) );
			$tld->setPlaceholder( "TLD" );
			$tld->setDefaultValue( "com" );
		}

		function validateEmailFormGroup( $formElement, $value )
		{
			/** @var FormGroup $formElement */
			$email = $formElement->findByName( self::GROUP_EMAIL_NAME )->getValue();
			$email = $email . "@";
			$email = $email . $formElement->findByName( self::GROUP_EMAIL_DOMAIN )->getValue();
			$email = $email . ".";
			$email = $email . $formElement->findByName( self::GROUP_EMAIL_TLD )->getValue();

			if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) === FALSE ) return "Not an email.";

			return TRUE;
		}
	}