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
    shell_exec( "cd ".TutoMVC::getRoot()." && git pull origin master" );

    $this->getFacade()->notificationCenter->add( "Pulled latest commits." );
  }
}
