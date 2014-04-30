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
    $this->getFacade()->notificationCenter->add( shell_exec( "cd ".TutoMVC::getRoot()." && git init" ) );
    $this->getFacade()->notificationCenter->add( shell_exec( "cd ".TutoMVC::getRoot()." && git remote add origin https://github.com/johannesbuvnas/com.tutomvc.wpplugin.git -f" ) );
  }
}
