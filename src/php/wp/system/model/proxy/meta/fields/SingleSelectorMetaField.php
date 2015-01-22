<?php
namespace tutomvc;

class SingleSelectorMetaField extends MetaField
{

	private $_taxonomy;

	public function __construct(
		$name,
		$title = "",
		$description = "",
		$options = array(),
		$defaultValue = NULL,
		$conditions = array()
	)
	{
		parent::__construct(
			$name,
			$title,
			$description,
			MetaField::TYPE_SELECTOR_SINGLE,
			array(
				MetaField::SETTING_OPTIONS => $options,
				MetaField::SETTING_DEFAULT_VALUE => $defaultValue
			),
			$conditions
		);
	}
}