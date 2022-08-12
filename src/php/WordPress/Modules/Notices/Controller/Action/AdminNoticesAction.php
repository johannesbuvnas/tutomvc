<?php

	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 18/11/15
	 * Time: 17:53
	 */
	namespace TutoMVC\WordPress\Modules\Notices\Controller\Action;

	use TutoMVC\WordPress\Core\Controller\Command\ActionCommand;
	use TutoMVC\WordPress\Modules\Notices\Model\Notice;
	use TutoMVC\WordPress\Modules\Notices\NoticesModule;

	class AdminNoticesAction extends ActionCommand
	{
		protected static $_executed = FALSE;

		function execute()
		{
			/** @var Notice $notification */
			foreach ( NoticesModule::getProxy()->getMap() as $notification )
			{
				$output = '
						<div class="notice ' . $notification->getName() . ' is-dismissible">
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
