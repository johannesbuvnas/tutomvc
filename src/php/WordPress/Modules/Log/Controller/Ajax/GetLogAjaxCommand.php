<?php

	namespace TutoMVC\WordPress\Modules\Log\Controller\Ajax;

	use TutoMVC\WordPress\Core\Controller\Command\AjaxCommand;
	use function switch_to_blog;
	use TutoMVC\WordPress\Modules\Log\LogModule;
	use function wp_verify_nonce;

	class GetLogAjaxCommand extends AjaxCommand
	{
		const NAME = "tutomvc_get_log";

		public function execute()
		{
			if ( wp_verify_nonce( $_GET[ 'nonce' ], $_GET[ 'action' ] ) )
			{
				$site = isset( $_GET[ 'site' ] ) ? $_GET[ 'site' ] : NULL;
				if ( $site )
				{
					switch_to_blog( $site );
				}
				$logFile = LogModule::getProxy()->getLogFileByTimestamp( strtotime( $_GET[ 'date' ] ) );
				if ( is_file( $logFile ) )
				{
					$log = file_get_contents( $logFile );
					$log = explode( "\n", $log );
					array_shift( $log );
					echo implode( "\n", $log );
					exit;
				}
				else
				{
					die( "Log not found." );
				}
			}

			die( "ERROR" );
		}
	}
