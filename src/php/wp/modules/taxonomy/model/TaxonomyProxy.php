<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 24/08/15
	 * Time: 15:43
	 */

	namespace tutomvc\wp\taxonomy;

	use tutomvc\wp\Proxy;

	class TaxonomyProxy extends Proxy
	{
		const NAME = __CLASS__;

		function onRegister()
		{
			add_action( "init", array($this, "init"), 1 );
		}

		/* HOOKS */
		function init()
		{
			/** @var Taxonomy $taxonomy */
			foreach ( $this->getMap() as $taxonomy )
			{
				$taxonomy->wp_register();
			}
		}
	}