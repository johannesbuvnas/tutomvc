<?php
namespace tutomvc;

class TutoMVCGitContentMediator extends Mediator
{
  const NAME = "menu/settings/tutomvc/content/git.php";
<<<<<<< HEAD
  const ACTION_DISPLAY_STATUS = "tutomvc/settings/git/display_status";
=======
>>>>>>> affebb7cb75c5d18df94e0c38db3bf735f0c58e0

  function __construct()
  {
    parent::__construct( self::NAME );
  }

  function onRegister()
  {
    $this->getFacade()->controller->registerCommand( new GitAddActionCommand() );
    $this->getFacade()->controller->registerCommand( new GitPullActionCommand() );

    add_action( "init", array( $this, "checkFormSubmission" ) );
<<<<<<< HEAD
    add_action( self::ACTION_DISPLAY_STATUS, array( $this, "onDisplayStatus" ) );
=======
>>>>>>> affebb7cb75c5d18df94e0c38db3bf735f0c58e0
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
<<<<<<< HEAD

  function onDisplayStatus()
  {
    if(is_dir( FileUtil::filterFileReference( TutoMVC::getRoot()."/.git" ) ))
    {
      $this->getFacade()->notificationCenter->add( "<pre>LATEST COMMIT\n\n".shell_exec( "cd ".TutoMVC::getRoot()." && git log -n 1" )."</pre>" );
    }
  }
=======
>>>>>>> affebb7cb75c5d18df94e0c38db3bf735f0c58e0
}
