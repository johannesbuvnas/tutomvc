<?php
namespace tutomvc;

class GitAddActionCommand extends ActionCommand
{
  function __construct()
  {
    parent::__construct( ActionCommand::GIT_ADD );
  }

  function execute()
  {
    $this->getFacade()->notificationCenter->add( $this->getFacade()->repository->init() );
  }
}
