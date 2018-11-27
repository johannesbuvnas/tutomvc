<?php

	namespace tutomvc\wp\metabox;

	use tutomvc\wp\core\controller\command\FilterCommand;

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