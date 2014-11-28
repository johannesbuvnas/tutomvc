<?php
namespace tutomvc;

class AdminNoticesCommand extends ActionCommand
{
	protected static $_executed = FALSE;

	function __construct()
	{
		parent::__construct( "admin_notices" );
	}

	function execute()
	{
		$mediator = $this->getFacade()->view->getMediator( NotificationMediator::NAME ) ? $this->getFacade()->view->getMediator( NotificationMediator::NAME ) : $this->getFacade()->view->registerMediator( new NotificationMediator() );
		
		foreach($this->getFacade()->notificationCenter->getMap() as $notification)
		{
			$mediator->setNotification( $notification );
			$mediator->render();
		}

		self::$_executed = TRUE;
	}

	public static function hasExecuted()
	{
		return self::$_executed;
	}
}