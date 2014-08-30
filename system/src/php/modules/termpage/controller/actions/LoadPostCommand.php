<?php namespace tutomvc\modules\termpage;
use \tutomvc\ActionCommand;

class LoadPostCommand extends ActionCommand
{
	const NAME = "load-post.php";

	function __construct()
	{
		parent::__construct( self::NAME );
	}

	function execute()
	{
		$screen = get_current_screen();

		if($screen->post_type == "page")
		{
			if(is_array($_GET) && array_key_exists("post", $_GET))
			{
				$term = TermPageModule::getTermForLandingPage( intval($_GET['post']) );
				if($term)
				{
					$this->getSystem()->notificationCenter->add( sprintf( __( 'This is the landing page for the term: %1$s', "tutomvc" ), edit_term_link( $term->name, "", "", $term, FALSE ) ) );
				}
			}
		}
	}
}
