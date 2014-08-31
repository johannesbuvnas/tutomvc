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
    $this->getFacade()->notificationCenter->add(  $this->getFacade()->repository->pull() );
  }
}
