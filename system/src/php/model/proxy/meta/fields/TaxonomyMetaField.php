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

	public function filterMetaValue( $metaValue )
	{
		if(!is_array($metaValue) || count($metaValue) == 0) return $metaValue;

		$newMap = array();
		foreach( $metaValue as $key => $value )
		{
			if(!filter_var( $value, FILTER_VALIDATE_INT ))
			{
				$term = wp_insert_term( $value, $this->getSetting( MetaField::SETTING_TAXONOMY ) );
				if(!is_wp_error($term) && is_array($term))
				{
					$newMap[$key] = array_pop( $term );
				}
				else
				{
					if(array_key_exists( "term_exists", $term->error_data )) $newMap[$key] = $term->error_data['term_exists'];
				}

			}
			else
			{
				$newMap[$key] = $value;
			}
		}

		return $newMap;
	}
}