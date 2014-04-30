<?php
namespace tutomvc;

class TutoMVCGitContentMediator extends Mediator
{
  const NAME = "menu/settings/tutomvc/content/git.php";

  function __construct()
  {
    parent::__construct( self::NAME );
  }

  function onRegister()
  {
    $this->getFacade()->controller->registerCommand( new GitAddActionCommand() );
    $this->getFacade()->controller->registerCommand( new GitPullActionCommand() );

    add_action( "init", array( $this, "checkFormSubmission" ) );
  }

  function getContent()
  {
    if(is_dir( FileUtil::filterFileReference( TutoMVC::getRoot()."/.git" ) ))
    {
      $this->setViewComponent( "menu/settings/tutomvc/content/git.pull.php" );
    }
    else
    {
      $this->setViewComponent( "menu/settings/tutomvc/content/git.add.php" );
    }

    return parent::getContent();
  }

  function checkFormSubmission()
  {
    if( is_array($_POST))
    {
      if( array_key_exists( ActionCommand::GIT_PULL, $_POST ) && wp_verify_nonce( $_POST[ ActionCommand::GIT_PULL ], ActionCommand::GIT_PULL ) ) do_action( ActionCommand::GIT_PULL );
      if( array_key_exists( ActionCommand::GIT_ADD, $_POST ) && wp_verify_nonce( $_POST[ ActionCommand::GIT_ADD ], ActionCommand::GIT_ADD ) ) do_action( ActionCommand::GIT_ADD );
    }
  }
}
