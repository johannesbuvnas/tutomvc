<?php

	namespace TutoMVC\Example\Form\Group;

	use TutoMVC\Example\Form\Group\ExampleFormGroup;
	use TutoMVC\Example\Form\Input\ExampleSelectorFormGroup;
	use TutoMVC\Form\Group\FissileFormGroup;

	class ExampleFissileFormGroup extends FissileFormGroup
	{
		const INPUT_GROUP        = "emails";
		const INPUT_GROUP_DOMAIN = "domain";
		const INPUT_GROUP_NAME   = "name";
		const NAME               = "example_fissile";
		const SOME_TEXT          = "some_text";
		const SOME_SELECTOR      = "some_selector";
		const WP_EDITOR          = "wp_editor";

		function __construct( $min = 1, $max = - 1 )
		{
			parent::__construct( self::NAME, "Example of clonable form group", "What the title says.", $min, $max );

			$this->add( new ExampleFormGroup() );
			$this->add( new ExampleSelectorFormGroup() );

			$this->setValidationMethod( array($this, "validateReq") );
		}

		function validateReq()
		{
			return "minst 1";
		}
	}
