<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 24/08/15
	 * Time: 15:43
	 */

	namespace tutomvc\wp\taxonomy;

	use tutomvc\wp\core\model\proxy\Proxy;
	use tutomvc\wp\log\LogModule;

	class TaxonomyProxy extends Proxy
	{
		const NAME = __CLASS__;

		function onRegister()
		{
			add_action( "init", array($this, "init"), 0 );
		}

		/* HOOKS */
		function init()
		{

			/** @var Taxonomy $taxonomy */
			foreach ( $this->getMap() as $taxonomy )
			{
				LogModule::add( "REGISTERING TAXONOMY: " . $taxonomy->getName() );
				$taxonomy->wp_register();
			}
		}
	}