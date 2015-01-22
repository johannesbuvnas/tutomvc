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
		$this->getFacade()->view->registerMediator( new CodeMediator() );
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
		// if( filter_var( ini_get('display_errors'), FILTER_VALIDATE_BOOLEAN ) !== TRUE ) return FALSE;

		$this->render( $exception );
	}

	function executeError( $errorLevel, $errorMessage, $errorFile, $errorLine, $errorContext = NULL )
	{
		if(strpos( $errorMessage, "magic_quotes_gpc")) return;
		
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
		if (ob_get_status()) ob_end_clean();

		echo '<link rel="stylesheet" href="'.$this->getFacade()->getURL( "style.css" ).'">';
		
		$this->getFacade()->log( $exception->getMessage()." @ ".$exception->getFile() );

		$this->_mediator->parse( "exception", $exception );
		$this->_mediator->render();
		?>
		<script type="text/javascript">
			var els = document.getElementsByClassName( "Backtrace" );
			for(var i in els)
			{
				var element = els[i];
				if(element && element.getElementsByClassName)
				{
					element.id = "backtrace" + i;
					var header = element.getElementsByClassName( "File" );
					if(header && header.length)
					{
						header = header[0];
						header.setAttribute( "data-id", "backtrace" + i );
						header.onclick = function(e)
						{
							var element = document.getElementById( this.getAttribute( "data-id" ) );
							console.log(element.className.indexOf( "Collapsed" ));
							if(element.className.indexOf( "Collapsed" ) > -1)
							{
								element.className = "Backtrace";
							}
							else
							{
								element.className = "Backtrace Collapsed";
							}
						};
					}
				}
			}
		</script>
		<?php
		exit;
	}
}