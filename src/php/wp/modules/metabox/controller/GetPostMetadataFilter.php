<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 07/05/15
	 * Time: 16:40
	 */

	namespace tutomvc\wp\metabox;

	use tutomvc\FilterCommand;
	use tutomvc\FormGroup;
	use tutomvc\wp\PostMetaUtil;

	class GetPostMetadataFilter extends FilterCommand
	{
		const NAME = "get_post_metadata";

		function __construct()
		{
			parent::__construct( self::NAME, 99, 4 );
		}

		function execute()
		{
			$value    = $this->getArg( 0 );
			$postID   = $this->getArg( 1 );
			$metaKey  = $this->getArg( 2 );
			$isSingle = $this->getArg( 3 );
			$postType = get_post_type( $postID );

			return MetaBoxModule::getInstance()->getProxy()->getPostMeta( $postID, $metaKey );
		}
	}