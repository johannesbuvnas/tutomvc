<?php
	namespace tutomvc\wp\setting;

	use tutomvc\Proxy;

	class SettingProxy extends Proxy
	{
		const NAME = __CLASS__;

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		/**
		 * @param Setting $item
		 * @param null $key
		 * @param bool $override
		 *
		 * @return mixed
		 */
		public function add( $item, $key = NULL, $override = FALSE )
		{
//			add_option( $item->getName(), "", "", $item->isAutoload() ? "yes" : "no" );
			add_settings_section( $item->getName(), $item->getLabel(), array(
				$item,
				"renderDescription"
			), $item->getMenuSlug() );
			add_settings_field( $item->getName(), $item->getLabel(), array(
				$this,
				"_onRenderSectionField"
			), $item->getMenuSlug(), $item->getName(), array("item" => $item) );
			register_setting( $item->getName(), $item->getName() );

			return parent::add( $item, $key, $override );
		}

		public function getOption()
		{

		}
	}