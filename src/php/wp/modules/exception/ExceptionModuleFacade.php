<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 23/09/16
	 * Time: 08:25
	 */

	namespace tutomvc\wp\exception;

	use Herrera\Json\Exception\Exception;
	use tutomvc\wp\core\facade\Facade;
	use tutomvc\wp\log\LogModule;

	class ExceptionModuleFacade extends Facade
	{
		const KEY = "com.tutomvc.wp.modules.exception";

		function __construct()
		{
			parent::__construct( self::KEY );
		}

		function prepController()
		{
			set_exception_handler( array($this, "executeException") );
			set_error_handler( array($this, "executeError") );
			register_shutdown_function( array($this, "executeShutdown") );
		}

		/* ACTIONS */
		function executeShutdown()
		{
			if ( $error = error_get_last() )
			{
				$this->executeError( $error[ 'type' ], $error[ 'message' ], $error[ 'file' ], $error[ 'line' ] );
			}
		}

		/**
		 * @param Exception|\Throwable $exception
		 */
		function executeException( $exception )
		{
			$this->renderException( $exception );
		}

		function executeError( $errorLevel, $errorMessage, $errorFile, $errorLine, $errorContext = NULL )
		{
			if ( strpos( $errorMessage, "magic_quotes_gpc" ) ) return;

			if ( error_reporting() & $errorLevel )
			{
				switch ( $errorLevel )
				{
					case E_USER_ERROR:
					case E_ERROR:
					case E_CORE_ERROR:
					case E_COMPILE_ERROR:

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

				$this->executeException( new \ErrorException( $type . ": " . $errorMessage, 0, $errorLevel, $errorFile, $errorLine ) );
			}
		}

		/**
		 * @param Exception|\Throwable $exception
		 */
		public function renderException( $exception )
		{
			if ( ob_get_status() ) ob_end_clean();

			LogModule::add( $exception->getMessage() . " @ " . $exception->getFile() );
			$this->render( "src/templates/errors/exception", NULL, array(
				"exception" => $exception
			) );
			?>
			<script type="text/javascript">
				var els = document.getElementsByClassName( "backtrace" );
				for ( var i in els )
				{
					var element = els[ i ];
					if ( element && element.getElementsByClassName )
					{
						element.id = "backtrace" + i;
						var header = element.getElementsByClassName( "title" );
						if ( header && header.length )
						{
							header = header[ 0 ];
							header.setAttribute( "data-id", "backtrace" + i );
							header.onclick = function ( e )
							{
								var element = document.getElementById( this.getAttribute( "data-id" ) );
								console.log( element.className.indexOf( "collapsed" ) );
								if ( element.className.indexOf( "collapsed" ) > -1 )
								{
									element.className = "backtrace";
								}
								else
								{
									element.className = "backtrace collapsed";
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