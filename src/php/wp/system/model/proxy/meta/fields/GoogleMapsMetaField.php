<?php
/**
 * Created by PhpStorm.
 * User: johannesbuvnas
 * Date: 13/10/14
 * Time: 10:07
 */

namespace tutomvc\wp;


class GoogleMapsMetaField extends MetaField
{
	public function __construct(
		$name,
		$title = "",
		$description = "",
		$maxCardinality = - 1,
		$label = "",
		$conditions = array()
	) {
		parent::__construct(
			$name,
			$title,
			$description,
			MetaField::TYPE_SELECTOR_GOOGLE_MAPS,
			array(
				MetaField::SETTING_MAX_CARDINALITY => $maxCardinality,
				MetaField::SETTING_LABEL           => $label
			),
			$conditions
		);
	}
} 