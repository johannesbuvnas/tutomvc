<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 07/05/15
	 * Time: 16:40
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\wp\core\controller\command\FilterCommand;

	class GetPostMetadataFilter extends FilterCommand
	{
		function execute()
		{
			$value    = func_get_arg( 0 );
			$postID   = func_get_arg( 1 );
			$metaKey  = func_get_arg( 2 );
			$isSingle = func_get_arg( 3 );
			$postType = get_post_type( $postID );

			return MetaBoxModule::getProxy()->getPostMetaByMetaKey( $postID, $metaKey, $value );
		}
	}