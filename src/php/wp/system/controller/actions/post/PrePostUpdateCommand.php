<?php
namespace tutomvc\wp;

class PrePostUpdateCommand extends ActionCommand
{
	const NAME = "pre_post_update";

	public function __construct()
	{
		parent::__construct( self::NAME );
	}

	function execute()
	{
		$postID = $this->getArg( 0 );
		$postType = get_post_type( $postID );

		foreach($this->getFacade()->postTypeCenter->getMap() as $customPostType)
		{
			if($customPostType->getName() == $postType)
			{
				$customPostType->pre_post_update( $postID );
			}
		}
	}
}