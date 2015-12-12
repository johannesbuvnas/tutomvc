<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/11/15
	 * Time: 17:53
	 */
	namespace tutomvc\wp\notification;

	class AdminNoticesAction extends \tutomvc\wp\ActionCommand
	{
		protected static $_executed = FALSE;

		function execute()
		{
			/** @var Notification $notification */
			foreach ( NotificationModule::getProxy()->getMap() as $notification )
			{
				$output = '
						<div class="' . $notification->getName() . '">
							<p>' . $notification->getValue() . '</p>
						</div>';
				echo $output;
			}

			self::$_executed = TRUE;
		}

		public static function hasExecuted()
		{
			return self::$_executed;
		}
	}