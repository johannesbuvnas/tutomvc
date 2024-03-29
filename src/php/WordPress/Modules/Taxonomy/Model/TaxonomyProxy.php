<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 24/08/15
	 * Time: 15:43
	 */

	namespace TutoMVC\WordPress\Modules\Taxonomy\Model;

	use TutoMVC\WordPress\Core\Model\Proxy\Proxy;
	use TutoMVC\WordPress\Modules\Taxonomy\Controller\Taxonomy;

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
				$taxonomy->wp_register();
			}
		}
	}
