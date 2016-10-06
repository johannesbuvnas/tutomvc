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
		const NAME             = "default";
		const TEXT             = "text";
		const EMAIL            = "email";
		const EMAIL_NAME       = "name";
		const EMAIL_DOMAIN     = "domain";
		const EMAIL_TOP_DOMAIN = "top_domain";

		function __construct()
		{
			parent::__construct( self::NAME, "Default form group", "Some default form group elements" );

			$this->addFormElement( new FormInput( self::TEXT, "Default text", "Some default text" ) );

			/** @var FormInputGroup $inputGroup */
			$inputGroup = $this->addFormElement( new FormInputGroup( self::EMAIL, "Input group", "Default input groups" ) );
			$inputGroup->setValidationMethod( array($this, "validateEmailFormGroup") );
			$inputGroup->addFormElement( new FormInput( self::EMAIL_NAME, NULL ) )
			           ->setPlaceholder( "Name" );
			$inputGroup->addFormElement( new FormInputAddon( "@" ) );
			$inputGroup->addFormElement( new FormInput( self::EMAIL_DOMAIN, NULL ) )
			           ->setPlaceholder( "Domain" )
			           ->setDefaultValue( "gmail" );
			$inputGroup->addFormElement( new FormInputAddon( ".com" ) );
		}

		function validateEmailFormGroup( $formElement, $value )
		{
			/** @var FormGroup $formElement */
			$email = $formElement->findFormElementByName( self::EMAIL_NAME )->getValue() . "@" . $formElement->findFormElementByName( self::EMAIL_DOMAIN )->getValue() . ".com";
			if ( filter_var( $email, FILTER_VALIDATE_EMAIL ) === FALSE ) return "Not an email.";

			return TRUE;
		}
	}