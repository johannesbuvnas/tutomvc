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
	use function wp_enqueue_editor;

	class AdminEnqueueScriptsAction extends ActionCommand
	{
		function execute()
		{
			wp_enqueue_style( TutoMVC::NAME, $this->getFacade()->getURL( "dist/tutomvc.css?ver=" . TutoMVC::getVersion() ) );
			wp_enqueue_script( TutoMVC::NAME, $this->getFacade()->getURL( "dist/tutomvc.js?ver=" . TutoMVC::getVersion() ) );
		}
	}