<?php

	namespace TutoMVC\WordPress\Modules\MetaBox\Controller\Filter;

	use TutoMVC\WordPress\Core\Controller\Command\FilterCommand;
	use TutoMVC\WordPress\Modules\MetaBox\MetaBoxModule;

	class GetUserMetadataFilter extends FilterCommand
	{
		function execute()
		{
			$value    = func_get_arg( 0 );
			$objectID = func_get_arg( 1 );
			$metaKey  = func_get_arg( 2 );

			return MetaBoxModule::getUserProxy()->getUserMetaByMetaKey( $objectID, $metaKey, FALSE, $value );
		}
	}
