<?php
namespace tutomvc;

/**
*	See http://xdsoft.net/jqplugins/datetimepicker/ for custom attributes.
*/
class DateTimeMetaField extends MetaField
{

	private $_taxonomy;

	public function __construct( 
		$name,
		$title = "", 
		$description = "",
		$customAttr = array()
	)
	{
		parent::__construct(
			$name,
			$title,
			$description,
			MetaField::TYPE_SELECTOR_DATETIME,
			array(
				MetaField::SETTING_CUSTOM_ATTR => $customAttr
			)
		);
	}
}