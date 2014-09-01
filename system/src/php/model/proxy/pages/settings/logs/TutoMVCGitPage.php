<?php
namespace tutomvc;

class TutoMVCGitPage extends AdminMenuPage
{
  const NAME = "tutomvc/git";


  function __construct()
  {
    parent::__construct( __( "Repository" ), __( "Repository" ), "manage_options", self::NAME );
  }

  function getContentMediatorName()
  {
    return "";
  }

  function onLoad()
  {
    $systemFacade = \tutomvc\Facade::getInstance( \tutomvc\Facade::KEY_SYSTEM );
    $systemFacade->notificationCenter->add( $systemFacade->repository->init() );
    $mediator = $systemFacade->view->registerMediator( new GitPullFormMediator() );
    if($systemFacade->repository->hasUnpulledCommits())
    {
      $systemFacade->notificationCenter->add( __( "You have unpulled commits.", "tutomvc" ) . $mediator->getContent() );
    }
    $systemFacade->notificationCenter->add( $systemFacade->repository->getStatus() );
  }
}
