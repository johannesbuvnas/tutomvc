<?php
namespace tutomvc;

class TaxonomyMetaField extends MetaField
{

	private $_taxonomy;

	public function __construct( 
		$name,
		$taxonomy,
		$title = "", 
		$description = "",
		$termsArguments = array(),
		$conditions = array()
	)
	{
		parent::__construct(
			$name,
			$title,
			$description,
			MetaField::TYPE_SELECTOR_MULTIPLE,
			array(
				MetaField::SETTING_TAXONOMY => $taxonomy,
				MetaField::SETTING_TAXONOMY_TERMS => $termsArguments
			),
			$conditions
		);
	}

	public function getSettings()
	{
		if(!is_array($this->getSetting( MetaField::SETTING_OPTIONS )))
		{
			$options = array();

			foreach(get_terms( $this->getSetting( MetaField::SETTING_TAXONOMY ), $this->getSetting( MetaField::SETTING_TAXONOMY_TERMS ) ) as $term)
			{
				$options[ $term->term_id ] = $term->name;
			}

			$this->setSetting( MetaField::SETTING_OPTIONS, $options );
		}

		return parent::getSettings();
	}
}