<?php

	namespace tutomvc\wp\log\actions;

	use tutomvc\wp\core\controller\command\AjaxCommand;
	use tutomvc\wp\log\LogModule;

	class GetLogAjaxCommand extends AjaxCommand
	{
		const NAME = "tutomvc_get_log";

		public function execute()
		{
			if ( wp_verify_nonce( $_GET[ 'nonce' ], $_GET[ 'action' ] ) )
			{
				$logFile = LogModule::getProxy()->getLogFileByTimestamp( strtotime( $_GET[ 'date' ] ) );
				if ( is_file( $logFile ) )
				{
					echo file_get_contents( $logFile );
					exit;
				}
			}

			die( "ERROR" );
		}
	}