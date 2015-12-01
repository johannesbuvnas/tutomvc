<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 14/04/15
	 * Time: 13:46
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\ActionCommand;

	class AddMetaBoxesAction extends ActionCommand
	{
		const NAME = "add_meta_boxes";

		function __construct()
		{
			parent::__construct( self::NAME, 10, 2 );
		}

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