<?php
namespace tutomvc;

class TutoMVCGitPage extends AdminMenuPage
{
  const NAME = "tutomvc/git";


  function __construct()
  {
    parent::__construct( __( "Git" ), __( "Git" ), "manage_options", self::NAME );
  }

  function getContentMediatorName()
  {
    return TutoMVCGitContentMediator::NAME;
  }

  function onLoad()
  {
    do_action( TutoMVCGitContentMediator::ACTION_DISPLAY_STATUS );
  }
}
