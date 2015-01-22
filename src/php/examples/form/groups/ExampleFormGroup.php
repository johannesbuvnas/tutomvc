<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/01/15
	 * Time: 17:30
	 */

	namespace tutomvc;

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
			$inputGroup->addFormElement( new FormInput( self::EMAIL_NAME, NULL ) )
			           ->setPlaceholder( "Name" );
			$inputGroup->addFormElement( new FormInputAddon( "@" ) );
			$inputGroup->addFormElement( new FormInput( self::EMAIL_DOMAIN, NULL ) )
			           ->setPlaceholder( "Domain" )
			           ->setDefaultValue( "gmail" );
			$inputGroup->addFormElement( new FormInputAddon( ".com" ) );

		}
	}