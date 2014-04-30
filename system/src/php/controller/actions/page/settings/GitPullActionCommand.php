<?php
namespace tutomvc;

class GitPullActionCommand extends ActionCommand
{
  function __construct()
  {
    parent::__construct( ActionCommand::GIT_PULL );
  }

  function execute()
  {
<<<<<<< HEAD
    shell_exec( "cd ".TutoMVC::getRoot()." && git reset --hard" );
=======
>>>>>>> affebb7cb75c5d18df94e0c38db3bf735f0c58e0
    shell_exec( "cd ".TutoMVC::getRoot()." && git pull origin master" );

    $this->getFacade()->notificationCenter->add( "Pulled latest commits." );
  }
}
