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
		// if(is_admin())
		// {
			$wpQuery = $this->getArg(0);
			foreach($this->getFacade()->postTypeCenter->getMap() as $postType)
			{
				if($postType->getName() == $wpQuery->get("post_type"))
				{
					if(!isset($_GET['orderby'])) $wpQuery->set( "orderby", $postType->getOrderBy() );
					if(!isset($_GET['order'])) $wpQuery->set( "order", $postType->getOrder() );
				}
			}
		// }
	}
}