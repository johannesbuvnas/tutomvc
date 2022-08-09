<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/04/15
	 * Time: 13:46
	 */

	namespace TutoMVC\WordPress\Modules\MetaBox\Controller\Action;

	use TutoMVC\WordPress\Core\Controller\Command\ActionCommand;
	use TutoMVC\WordPress\Modules\MetaBox\Controller\MetaBox;
	use TutoMVC\WordPress\Modules\MetaBox\MetaBoxModule;
	use function add_meta_box;

	class AddMetaBoxesAction extends ActionCommand
	{
		function execute()
		{
			$screen = func_get_arg( 0 );
			/** @var MetaBox $metaBox */
			foreach ( MetaBoxModule::getProxy()->getMap() as $metaBox )
			{
				if ( in_array( $screen, $metaBox->getPostTypes() ) )
				{
					add_meta_box(
						$metaBox->getElementName(),
						$metaBox->getLabel(),
						array($metaBox, "render"),
						$screen,
						$metaBox->getContext(),
						$metaBox->getPriority()
					);
				}
			}
		}
	}
