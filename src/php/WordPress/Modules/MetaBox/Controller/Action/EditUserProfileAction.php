<?php

	namespace TutoMVC\WordPress\Modules\MetaBox\Controller\Action;

	use TutoMVC\WordPress\Core\Controller\Command\ActionCommand;
	use TutoMVC\WordPress\Modules\MetaBox\Controller\UserMetaBox;
	use TutoMVC\WordPress\Modules\MetaBox\MetaBoxModule;

	class EditUserProfileAction extends ActionCommand
	{
		public function execute()
		{
			$userID = func_get_arg( 0 );

			/** @var UserMetaBox $metaBox */
			foreach ( MetaBoxModule::getUserProxy()->getMap() as $metaBox )
			{
				if ( !empty( $_POST ) && isset( $_POST[ $metaBox->getName() ] ) )
				{
					if ( $metaBox->parse( $_POST ) )
					{
						$metaBox->update( $userID );
					}
					//TODO: Add admin notification if metabox contains errors
				}
			}
		}
	}
