<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 24/08/15
	 * Time: 15:43
	 */

	namespace tutomvc\wp\taxonomy;

	use tutomvc\Proxy;

	class TaxonomyProxy extends Proxy
	{
		const NAME = __CLASS__;

		/**
		 * @param Taxonomy $item
		 * @param null $key
		 * @param bool $override
		 *
		 * @return mixed
		 */
		public function add( $item, $key = NULL, $override = FALSE )
		{
			$item->wp_register();

			return parent::add( $item, $key, $override );
		}
	}