<?php
	/** @var Exception $exception */
?>
<style type="text/css">
	/* .tutomvc-error-handler */
	.tutomvc-error-handler
	{
		width: 100%;
		font-family: HelveticaNeue-Light, "Helvetica Neue Light", "Helvetica Neue", sans-serif;
		font-size: 14px;
		line-height: 1.5em;
		color: #333;
	}

	.tutomvc-error-handler > .inner
	{
		width: 560px;
		padding: 30px 30px;
		margin: 0 auto;
		background: #ebebeb;
	}

	.tutomvc-error-handler .title
	{
		font-weight: 600;
		color: #e04b44;
	}

	.tutomvc-error-handler .description
	{
		color: #666;
		font-size: 12px;
	}

	.tutomvc-error-handler .code-wrapper
	{
		width: 100%;
		background: #fff;
		overflow: scroll;
		color: #666;
		margin-bottom: 10px;
	}

	.tutomvc-error-handler .backtrace
	{
		margin: 5px 0px;
	}

	.tutomvc-error-handler .backtrace .title
	{
		background: #f5f5f5;
		color: #666;
		font-size: 12px;
		padding: 10px;
		cursor: pointer;
	}

	.tutomvc-error-handler .backtrace .title span
	{
		padding-left: 15px;
	}

	.tutomvc-error-handler .backtrace .title span.minus,
	.tutomvc-error-handler .backtrace .title span.plus
	{
		font-weight: normal;
	}

	.tutomvc-error-handler .backtrace.collapsed .title span.minus,
	.tutomvc-error-handler .backtrace .title span.plus
	{
		display: none;
	}

	.tutomvc-error-handler .backtrace.collapsed .title span.plus
	{
		display: inline-block;
	}

	.tutomvc-error-handler .backtrace.collapsed .code-wrapper
	{
		display: none;
	}

	.tutomvc-error-handler code
	{
		font-size: 10px;
		background: 0;
		padding: 0;
		margin: 0;
		line-height: 1.2em;
	}

	.tutomvc-error-handler pre
	{
		margin: 0;
		padding: 0px 5px;
		line-height: 1.2em;
		display: block;
		font-size: 12px;
		word-break: break-all;
		word-wrap: break-word;
		color: #333333;
		background-color: #fff;
		border: 0;
		border-radius: 0;
		width: 100%;
	}

	.tutomvc-error-handler pre.line
	{
		background: #e04b44;
		color: #FFF;
	}
	.tutomvc-error-handler pre.line code
	{
		/*line-height: 2em;*/
	}

	.tutomvc-error-handler .code-wrapper .line-index
	{
		color: rgba(0, 0, 0, .4);
	}
	.tutomvc-error-handler .code-wrapper pre.line .line-index
	{
		color: rgba(255, 255, 255, .5);
	}

	.tutomvc-error-handler .ExceptionLine .line-index
	{
		color: #FFF;
	}

	/* end .tutomvc-error-handler */
</style>
<div class="tutomvc-error-handler">
	<div class="inner">
		<h1>Tuto MVC</h1>
		<p>
			<span class="title"><?php
					echo $exception->getMessage(); ?></span>
		</p>
		<?php
			\tutomvc\wp\exception\ExceptionModule::getInstance()->render( "src/templates/errors/exception", "code", array(
				"filePath" => $exception->getFile(),
				"line"     => $exception->getLine(),
				"expanded" => TRUE
			) );

			$backtraceNum = - 1;
			foreach ( debug_backtrace() as $backtrace )
			{
				if ( array_key_exists( "file", $backtrace ) && $backtrace[ 'file' ] == $exception->getFile() || $backtraceNum > - 1 ) $backtraceNum ++;
				if ( $backtraceNum >= 1 )
				{
					if ( array_key_exists( "file", $backtrace ) && array_key_exists( "line", $backtrace ) )
					{
						\tutomvc\wp\exception\ExceptionModule::getInstance()->render( "src/templates/errors/exception", "code", array(
							"filePath" => $backtrace[ 'file' ],
							"line"     => $backtrace[ 'line' ],
							"expanded" => FALSE
						) );
					}
				}
			}
		?>
	</div>
</div>