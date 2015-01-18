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
		const NAME               = "default";
		const TEXT               = "text";
		const INPUT_GROUP        = "input_group";
		const INPUT_GROUP_NAME   = "name";
		const INPUT_GROUP_DOMAIN = "domain";

		function __construct()
		{
			parent::__construct( self::NAME, "Default form group", "Some default form group elements" );

			$this->addFormElement( new FormInput( self::TEXT, "Default text", "Some default text" ) );

			/** @var FormInputGroup $inputGroup */
			$inputGroup = $this->addFormElement( new FormInputGroup( self::INPUT_GROUP, "Input group", "Default input groups" ) );
			$inputGroup->addFormElement( new FormInput( self::INPUT_GROUP_NAME, NULL ) )
			           ->setPlaceholder( "Name" );
			$inputGroup->addFormElement( new FormInputAddon( "@" ) );
			$inputGroup->addFormElement( new FormInput( self::INPUT_GROUP_DOMAIN, NULL ) )
			           ->setPlaceholder( "Domain" );
		}
	}