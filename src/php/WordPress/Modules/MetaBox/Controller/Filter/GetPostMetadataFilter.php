<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 07/05/15
	 * Time: 16:40
	 */

	namespace TutoMVC\WordPress\Modules\MetaBox\Controller\Filter;

	use TutoMVC\WordPress\Core\Controller\Command\FilterCommand;
	use TutoMVC\WordPress\Modules\MetaBox\MetaBoxModule;
	use function get_post_type;

	class GetPostMetadataFilter extends FilterCommand
	{
		function execute()
		{
			$value    = func_get_arg( 0 );
			$postID   = func_get_arg( 1 );
			$metaKey  = func_get_arg( 2 );
			$isSingle = func_get_arg( 3 );
			$postType = get_post_type( $postID );

			return MetaBoxModule::getProxy()->getPostMetaByMetaKey( $postID, $metaKey, FALSE, $value );
		}
	}
