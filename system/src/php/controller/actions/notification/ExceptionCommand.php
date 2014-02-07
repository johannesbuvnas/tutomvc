<?php
namespace tutomvc;

class ExceptionCommand extends ActionCommand
{
	/* VARS */
	protected $_mediator;


	function register()
	{
		set_exception_handler( array( $this, "executeException" ) );
		set_error_handler( array( $this, "executeError" ) );
		register_shutdown_function( array( $this, "executeShutdown" ) );
	}

	public function onRegister()
	{
		$this->_mediator = $this->getFacade()->view->registerMediator( new CodeMediator() );
		$this->_mediator = $this->getFacade()->view->registerMediator( new ExceptionMediator() );
	}

	/* ACTIONS */
	function executeShutdown()
	{
		if($error = error_get_last())
		{
			$this->executeError( $error['type'], $error['message'], $error['file'], $error['line'] );
		}
	}
	function executeException( $exception )
	{
		$this->render( $exception );
	}

	function executeError( $errorLevel, $errorMessage, $errorFile, $errorLine, $errorContext = NULL )
	{
		if (error_reporting() & $errorLevel)
		{
			switch ( $errorLevel )
			{
				case E_USER_ERROR:
				case E_ERROR:
				case E_CORE_ERROR:
				case E_COMPILE_ERROR:
				case E_USER_ERROR:

					$type = 'FATAL ERROR';

				break;
				case E_USER_WARNING:
				case E_WARNING:

					$type = 'WARNING';

				break;
				case E_USER_NOTICE:
				case E_NOTICE:
				case @E_STRICT:

					$type = 'NOTICE';

				break;
				case @E_RECOVERABLE_ERROR:

					$type = 'CATCHABLE';

				break;
				default:

					$type = 'UNKNOWN';

				break;
			}

			$this->executeException( new \ErrorException( $type.": ".$errorMessage, 0, $errorLevel, $errorFile, $errorLine ) );
		}
	}

	public function render( $exception )
	{
		echo '<link rel="stylesheet" href="'.$this->getFacade()->getURL( "assets/css/tutomvc.admin.css" ).'">';
		
		$this->getFacade()->log( $exception->getMessage()." @ ".$exception->getFile() );

		$this->_mediator->parse( "exception", $exception );
		$this->_mediator->render();
		exit;
	}
}