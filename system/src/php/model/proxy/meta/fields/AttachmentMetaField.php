<?php
namespace tutomvc;

class AttachmentMetaField extends MetaField
{

	private $_taxonomy;

	public function __construct( 
		$name,
		$title = "", 
		$description = "",
		$maxCardinality = 1,
		$filter = NULL,
		$buttonTitle = "Select",
		$conditions = array()
	)
	{
		parent::__construct(
			$name,
			$title,
			$description,
			MetaField::TYPE_ATTACHMENT,
			array(
				MetaField::SETTING_MAX_CARDINALITY => $maxCardinality,
				MetaField::SETTING_FILTER => $filter,
				MetaField::SETTING_BUTTON_TITLE => $buttonTitle
			),
			$conditions
		);
	}
}