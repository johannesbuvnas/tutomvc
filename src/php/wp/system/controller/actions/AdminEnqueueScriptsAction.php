<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 12/12/15
	 * Time: 10:17
	 */

	namespace tutomvc\wp;

	class AdminEnqueueScriptsAction extends ActionCommand
	{
		function execute()
		{
			wp_enqueue_style( TutoMVC::NAME, $this->getFacade()->getURL( "dist/css/tutomvc.css?ver=" . TutoMVC::VERSION ) );
			wp_enqueue_script( TutoMVC::NAME, $this->getFacade()->getURL( "dist/js/tutomvc.min.js?ver=" . TutoMVC::VERSION ) );
			wp_enqueue_script( "tutomvc-wpattachmentforminput", $this->getFacade()->getURL( "src/js/wp/jquery.wpattachmentforminput.js?ver=" . TutoMVC::VERSION ) );
		}
	}