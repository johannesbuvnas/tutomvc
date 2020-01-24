<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 16/04/15
	 * Time: 09:42
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\wp\core\controller\command\ActionCommand;
	use function array_key_exists;
	use function func_get_arg;
	use function get_post_type;
	use function in_array;

	class SavePostAction extends ActionCommand
	{
		function execute()
		{
			$postID = func_get_arg( 0 );

			/** @var MetaBox $metaBox */
			foreach ( MetaBoxModule::getProxy()->getMap() as $metaBox )
			{
				if ( !empty( $_POST ) && in_array( get_post_type( $postID ), $metaBox->getPostTypes() ) && isset( $_POST[ $metaBox->getName() ] ) )
				{
					if ( array_key_exists( $metaBox->getName(), $_POST ) )
					{
						if ( !empty( $_POST[ $metaBox->getName() ] ) )
						{
							$metaBox->parse( $_POST );
						}
						else
						{
							$metaBox->setFissions( 0 );
						}
						$metaBox->update( $postID );
					}
					//TODO: Add admin notification if metabox contains errors
				}
			}
		}
	}