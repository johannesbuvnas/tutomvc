<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 16/04/15
	 * Time: 09:42
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\wp\core\controller\command\ActionCommand;
	use tutomvc\wp\log\LogModule;

	class SavePostAction extends ActionCommand
	{
		function execute()
		{
			$postID = func_get_arg( 0 );

			/** @var MetaBox $metaBox */
			foreach ( MetaBoxModule::getProxy()->getMap() as $metaBox )
			{
				if ( !empty($_POST) && in_array( get_post_type( $postID ), $metaBox->getPostTypes() ) && isset($_POST[ $metaBox->getName() ]) )
				{
					if ( $metaBox->parse( $_POST ) )
					{
						$metaBox->update( $postID );
					}
					//TODO: Add admin notification if metabox contains errors
				}
			}
		}
	}