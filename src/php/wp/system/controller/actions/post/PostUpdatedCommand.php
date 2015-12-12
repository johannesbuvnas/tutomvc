<?php
namespace tutomvc\wp;

class PostUpdatedCommand extends ActionCommand
{
	const NAME = "post_updated";

	public function __construct()
	{
		parent::__construct( self::NAME );
	}

	function execute()
	{
		$postAfter = $this->getArg( 0 );
		$postType = get_post_type( $postAfter );
		$postBefore = $this->getArg( 1 );

		foreach($this->getFacade()->postTypeCenter->getMap() as $customPostType)
		{
			if($customPostType->getName() == $postType)
			{
				$customPostType->post_updated( $postAfter, $postBefore );
			}
		}
	}
}