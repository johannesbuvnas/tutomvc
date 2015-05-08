<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 16/04/15
	 * Time: 09:42
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\ActionCommand;

	class SavePostAction extends ActionCommand
	{
		const NAME = "save_post";

		function __construct()
		{
			parent::__construct( self::NAME );
		}

		function execute()
		{
			$postID = $this->getArg( 0 );
			$screen = get_current_screen();

			/** @var MetaBox $metaBox */
			foreach ( $this->getFacade()->model->getProxy( MetaBoxProxy::NAME )->getMap() as $metaBox )
			{
				if ( in_array( $screen->post_type, $metaBox->getPostTypes() ) )
				{
					if ( $metaBox->parse( $_POST ) )
					{
//						var_dump( $_POST[ $metaBox->getName() ] );
//						var_dump( $metaBox->getValue() );
//						exit;
						$metaBox->update( $postID );
					}
				}
			}
		}
	}