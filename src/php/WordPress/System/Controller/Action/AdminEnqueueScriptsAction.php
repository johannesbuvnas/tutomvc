<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 12/12/15
	 * Time: 10:17
	 */

	namespace TutoMVC\WordPress\System\Controller\Action;

	use TutoMVC\WordPress\Core\Controller\Command\ActionCommand;
	use TutoMVC\WordPress\TutoMVC;

	class AdminEnqueueScriptsAction extends ActionCommand
	{
		function execute()
		{
			wp_enqueue_style( TutoMVC::NAME, $this->getFacade()->getURL( "dist/tutomvc.css?ver=" . TutoMVC::getVersion() ) );
			wp_enqueue_script( TutoMVC::NAME, $this->getFacade()->getURL( "dist/tutomvc.js?ver=" . TutoMVC::getVersion() ) );
		}
	}
