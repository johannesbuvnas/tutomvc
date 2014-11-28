<?php
namespace tutomvc;

/**
 * Class WhitelistOptionsFilter
 * Adding custom options from SettingsProxy to whitelist so that they will be saved.
 * @package tutomvc
 */
class WhitelistOptionsFilter extends FilterCommand
{
	const NAME = "whitelist_options";

	function __construct()
	{
		parent::__construct( self::NAME, 0, 1 );
	}

	function execute( $whitelist_options )
	{
		foreach($this->getSystem()->settingsCenter->getMap() as $settingsItem)
		{
			if(!array_key_exists($settingsItem->getMenuSlug(), $whitelist_options)) $whitelist_options[ $settingsItem->getMenuSlug() ] = array();
			foreach($settingsItem->getFields() as $metaField)
			{
				$whitelist_options[ $settingsItem->getMenuSlug() ][] = $metaField->getName();
			}
		}

		return $whitelist_options;
	}
}