<?php
namespace tutomvc;

class GetOptionFilterCommand extends FilterCommand
{
	protected $_optionName;

	function __construct( $optionName )
	{
		$this->_optionName = $optionName;

		parent::__construct( "option_" . $optionName );
	}

	public function execute( $value )
	{
		$field = $this->getFacade()->settingsCenter->getSectionFieldByOptionName( $this->_optionName );

		return $field ? apply_filters( FilterCommand::META_VALUE, $value, $field ) : $value;
	}
}