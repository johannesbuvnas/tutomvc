<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 22/01/15
	 * Time: 21:10
	 */

	namespace tutomvc\examples\form\groups;

	use tutomvc\core\form\groups\FormGroup;
	use tutomvc\core\form\inputs\Select2FormInput;
	use tutomvc\core\form\inputs\SelectFormInput;
	use tutomvc\core\form\inputs\SelectPickerFormInput;

	class ExampleSelectorFormGroup extends FormGroup
	{
		const NAME                  = "selectors";
		const MULTIPLE              = "multiple";
		const MULTIPLE_SELECTPICKER = "multiple_selectpicker";
		const TAGS                  = "tags";
		const SINGLE                = "single";

		function __construct()
		{
			parent::__construct( self::NAME, "A bunch of selectors in a form group.", "Selfdescriptive title..." );

			/** @var SelectFormInput $multipleSelector */
			$multipleSelector = $this->add( new SelectFormInput( self::MULTIPLE, "Multiple selector", "Select multiple values.", FALSE, FALSE, "Placeholder text" ) );
			$multipleSelector->addOption( "Sweden", "sv", "Nordic countries", "Title: Sweden" )
			                 ->addOption( "Denmark", "dk", "Nordic countries" )
			                 ->addOption( "Norway", "no", "Nordic countries" )
			                 ->addOption( "Finland", "fi", "Nordic countries" );

			$this->add( new SelectPickerFormInput( self::MULTIPLE_SELECTPICKER, "Selectpicker", "", FALSE, FALSE, "Placeholder text" ) )
			     ->addOption( "Sweden", "sv", "Nordic countries", "Title: Sweden" )
			     ->addOption( "Denmark", "dk", "Nordic countries" )
			     ->addOption( "Norway", "no", "Nordic countries" )
			     ->addOption( "Finland", "fi", "Nordic countries" )
			     ->setLiveSearchEnabled( TRUE );

			$this->add( new Select2FormInput( self::TAGS, "Tags", "", FALSE, FALSE, "Placeholder text" ) )
			     ->addOption( "Sweden", "sv", "Nordic countries", "Title: Sweden" )
			     ->addOption( "Denmark", "dk", "Nordic countries" )
			     ->addOption( "Norway", "no", "Nordic countries" )
			     ->addOption( "Finland", "fi", "Nordic countries" )
			     ->setSelect2Options( array(
				     "tags" => TRUE,
				     //				     "tokenSeparators" => array(",", " ")
			     ) );

			/** @var SelectFormInput $singleSelector */
			$singleSelector = $this->add( new SelectFormInput( self::SINGLE, "Single selector", "Select only one value.", TRUE, FALSE ) );
			$singleSelector->addOption( "Sweden", "sv", "Nordic countries", "Title: Sweden" )
			               ->addOption( "Denmark", "dk", "Nordic countries" )
			               ->addOption( "Norway", "no", "Nordic countries" )
			               ->addOption( "Finland", "fi", "Nordic countries" )
			               ->setDefaultValue( "no" );
		}
	}