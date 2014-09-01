<?php
namespace tutomvc;

class GitPullFormMediator extends Mediator
{
  const NAME = "menu/settings/tutomvc/content/form-git_pull";
  const ACTION_PULL = "tutomvc/action/git_pull";


  function __construct()
  {
    parent::__construct( self::NAME );
  }

  function onRegister()
  {
    if(is_array($_POST) && array_key_exists(self::ACTION_PULL, $_POST))
    {
      $this->getFacade()->repository->pull();
    }
  }
}