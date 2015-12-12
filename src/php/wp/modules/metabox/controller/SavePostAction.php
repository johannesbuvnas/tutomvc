<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 16/04/15
	 * Time: 09:42
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\wp\ActionCommand;

	class SavePostAction extends ActionCommand
	{
		function execute()
		{
			$postID = func_get_arg( 0 );
			$screen = get_current_screen();

			if ( $screen )
			{
				/** @var MetaBox $metaBox */
				foreach ( MetaBoxModule::getProxy()->getMap() as $metaBox )
				{
					if ( in_array( $screen->post_type, $metaBox->getPostTypes() ) )
					{
						$metaBox->parse( $_POST );
						$metaBox->update( $postID );
						//TODO: Add admin notification if metabox contains errors
					}
				}
			}
		}
	}