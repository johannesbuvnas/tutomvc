<?php
namespace tutomvc;

class TextAreaMetaField extends MetaField
{

	private $_taxonomy;

	public function __construct( 
		$name,
		$title = "", 
		$description = "",
		$rows = 5,
		$readOnly = FALSE,
		$defaultValue = NULL,
		$conditions = array()
	)
	{
		parent::__construct(
			$name,
			$title,
			$description,
			MetaField::TYPE_TEXTAREA,
			array(
				MetaField::SETTING_ROWS => $rows,
				MetaField::SETTING_READ_ONLY => $readOnly,
				MetaField::SETTING_DEFAULT_VALUE => $defaultValue
			),
			$conditions
		);
	}
}