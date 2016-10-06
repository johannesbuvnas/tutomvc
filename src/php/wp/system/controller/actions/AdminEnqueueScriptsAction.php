<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 12/12/15
	 * Time: 10:17
	 */

	namespace tutomvc\wp\system\controller\actions;

	use tutomvc\wp\core\controller\command\ActionCommand;
	use tutomvc\wp\TutoMVC;

	class AdminEnqueueScriptsAction extends ActionCommand
	{
		function execute()
		{
			wp_enqueue_style( TutoMVC::NAME, $this->getFacade()->getURL( "dist/css/tutomvc.css?ver=" . TutoMVC::VERSION ) );
			wp_enqueue_script( TutoMVC::NAME, $this->getFacade()->getURL( "dist/js/tutomvc.min.js?ver=" . TutoMVC::VERSION ) );
		}
	}