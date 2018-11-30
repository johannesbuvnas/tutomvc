<?php

	namespace tutomvc\wp\metabox;

	use tutomvc\wp\core\controller\command\ActionCommand;

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