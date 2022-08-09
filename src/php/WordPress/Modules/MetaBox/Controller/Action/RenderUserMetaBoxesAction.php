<?php

	namespace TutoMVC\WordPress\Modules\MetaBox\Controller\Action;

	use TutoMVC\WordPress\Core\Controller\Command\ActionCommand;
	use TutoMVC\WordPress\Modules\MetaBox\Controller\UserMetaBox;
	use TutoMVC\WordPress\Modules\MetaBox\MetaBoxModule;

	class RenderUserMetaBoxesAction extends ActionCommand
	{
		public function execute()
		{
			$user = func_get_arg( 0 );
			/** @var UserMetaBox $metaBox */
			foreach ( MetaBoxModule::getUserProxy()->getMap() as $metaBox )
			{
				$metaBox->render( $user );
			}
		}
	}
