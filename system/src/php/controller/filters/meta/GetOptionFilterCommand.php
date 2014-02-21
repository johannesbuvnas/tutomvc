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

	public function execute()
	{
		$value = $this->getArg(0);

		$field = $this->getFacade()->settingsCenter->getSectionFieldByOptionName( $this->_optionName );

		return $field ? apply_filters( FilterCommand::META_VALUE, NULL, $value, $field ) : $value;
	}
}