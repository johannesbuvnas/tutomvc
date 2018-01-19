<?php

	namespace tutomvc\wp\log\actions;

	use tutomvc\wp\core\controller\command\AjaxCommand;

	class GetLogAjaxCommand extends AjaxCommand
	{
		const NAME = "tutomvc_get_log";

		public function execute()
		{
			if ( wp_verify_nonce( $_GET[ 'nonce' ], $_GET[ 'action' ] ) )
			{
				die( "SUCCESS" );
			}

			die( "ERROR" );
		}
	}