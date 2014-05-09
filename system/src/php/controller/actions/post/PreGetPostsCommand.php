<?php
namespace tutomvc;

class PreGetPostsCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct( "pre_get_posts" );
	}

	function execute()
	{
		$wpQuery = $this->getArg(0);
		foreach($this->getFacade()->postTypeCenter->getMap() as $postType)
		{
			if($postType->getName() == $wpQuery->get("post_type"))
			{
				$postType->_pre_get_posts( $wpQuery );
			}
		}
	}
}