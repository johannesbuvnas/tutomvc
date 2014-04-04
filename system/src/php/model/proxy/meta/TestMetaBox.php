<?php
namespace tutomvc;

class TestMetaBox extends MetaBox
{
	const NAME = "test";

	function __construct()
	{
		parent::__construct(
			self::NAME,
			"Test Meta Box",
			array( "post" ),
			MetaBox::CARDINALITY_UNLIMITED,
			MetaBox::CONTEXT_NORMAL,
			MetaBox::PRIORITY_HIGH
		);

		$this->addField( new MetaField(
			MetaField::TYPE_TEXT,
			"Text",
			"",
			MetaField::TYPE_TEXT,
			array(
				MetaField::SETTING_READ_ONLY => FALSE
			)
		) );

		$this->addField( new MetaField(
			MetaField::TYPE_TEXTAREA,
			"Textarea",
			"",
			MetaField::TYPE_TEXTAREA,
			array(
				MetaField::SETTING_ROWS => 5
			)
		) );

		$this->addField( new MetaField(
			MetaField::TYPE_TEXTAREA_WYSIWYG,
			"Textarea WYSIWYG",
			"",
			MetaField::TYPE_TEXTAREA_WYSIWYG,
			array(
			)
		) );

		$this->addField( new MetaField(
			MetaField::TYPE_ATTACHMENT,
			"Attachment",
			"",
			MetaField::TYPE_ATTACHMENT,
			array(
				MetaField::SETTING_FILTER => array(),
				MetaField::SETTING_BUTTON_TITLE => "[BUTTON TITLE]",
				MetaField::SETTING_MAX_CARDINALITY => MetaField::CARDINALITY_UNLIMITED
			)
		) );

		$this->addField( new MetaField(
			MetaField::TYPE_SELECTOR_SINGLE,
			"Selector: single",
			"",
			MetaField::TYPE_SELECTOR_SINGLE,
			array(
				MetaField::SETTING_LABEL => "[LABEL]",
				MetaField::SETTING_OPTIONS => array(
					"[OPTION_VALUE_1]" => "[OPTION NAME 1]",
					"[OPTION_VALUE_2]" => "[OPTION NAME 2]"
				)
			)
		) );

		$this->addField( new MetaField(
			MetaField::TYPE_LINK,
			"Link",
			"",
			MetaField::TYPE_LINK,
			array(
			)
		) );
	}
}