<style type="text/css">
    /* .tutomvc-log-handler */
    .tutomvc-log-handler
    {
        width: 550px;
        font-family: HelveticaNeue-Light, "Helvetica Neue Light", "Helvetica Neue", sans-serif;
        font-size: 14px;
        line-height: 1.5em;
        color: #333;
    }

    .tutomvc-log-handler > .inner
    {
        width: 550px;
        padding: 30px 30px;
        margin: 0 auto;
        background: #ebebeb;
    }

    .tutomvc-log-handler .title
    {
        font-weight: 600;
        color: #0099FF;
    }

    .tutomvc-log-handler .description
    {
        color: #666;
        font-size: 12px;
    }

    .tutomvc-log-handler .code-wrapper
    {
        width: 100%;
        background: #fff;
        overflow: scroll;
        color: #666;
        margin-bottom: 10px;
    }

    .tutomvc-log-handler .backtrace
    {
        margin: 5px 0px;
    }

    .tutomvc-log-handler .backtrace .title
    {
        background: #f5f5f5;
        color: #666;
        font-size: 12px;
        padding: 10px;
        cursor: pointer;
    }

    .tutomvc-log-handler .backtrace .title span
    {
        padding-left: 15px;
    }

    .tutomvc-log-handler .backtrace .title span.minus,
    .tutomvc-log-handler .backtrace .title span.plus
    {
        font-weight: normal;
    }

    .tutomvc-log-handler .backtrace.collapsed .title span.minus,
    .tutomvc-log-handler .backtrace .title span.plus
    {
        display: none;
    }

    .tutomvc-log-handler .backtrace.collapsed .title span.plus
    {
        display: inline-block;
    }

    .tutomvc-log-handler .backtrace.collapsed .code-wrapper
    {
        display: none;
    }

    .tutomvc-log-handler code
    {
        font-size: 10px;
        background: 0;
        padding: 0;
        margin: 0;
        line-height: 1.2em;
    }

    .tutomvc-log-handler pre
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

    .tutomvc-log-handler pre.line
    {
        background: #e04b44;
        color: #FFF;
    }

    .tutomvc-log-handler pre.line code
    {
        /*line-height: 2em;*/
    }

    .tutomvc-log-handler .code-wrapper .line-index
    {
        color: rgba(0, 0, 0, .4);
    }

    .tutomvc-log-handler .code-wrapper pre.line .line-index
    {
        color: rgba(255, 255, 255, .5);
    }

    .tutomvc-log-handler .ExceptionLine .line-index
    {
        color: #FFF;
    }

    /* end .tutomvc-log-handler */
</style>
<div class="wrap">
    <div class="container-fluid">
        <h1>Logs</h1>
        <form id="logs" method="get" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">
            <input type="hidden" name="action" value="<?php echo \tutomvc\wp\log\actions\GetLogAjaxCommand::NAME; ?>"/>
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce( \tutomvc\wp\log\actions\GetLogAjaxCommand::NAME ); ?>"/>
            <input type="date" class="datepicker" name="date" value="<?php echo date( "Y-m-d" ); ?>"/>
        </form>
        <script type="text/javascript">
            jQuery( document ).ready( function ( $ ) {
                var $form = $( "#logs" );
                var $datepicker = $( "#logs .datepicker" );
                $datepicker.on( "change", function () {
                    $.ajax( {
                        type: $form.attr( "method" ),
                        url: $form.attr( "action" ),
                        data: $form.serialize(), // serializes the form's elements.
                        success: function ( result ) {
                            $( ".tutomvc-log-handler .title" ).text( $( "input[name=date]" ).val() );
                            $( ".tutomvc-log-handler .code-wrapper" ).html( "<pre><code>" + result + "</code></pre>" );
                            $( ".tutomvc-log-handler" ).removeClass( "hidden" );
                        },
                        error: function ( result ) {
                            console.error( result );
                        }
                    } );
                } );
                $datepicker.trigger( "change" );
            } );
        </script>
        <div class="tutomvc-log-handler hidden">
            <div class="inner">
                <h3 class="title">Tuto MVC log</h3>
                <div class="code-wrapper">
					<?php
						foreach ( $lines as $key => $value )
						{
							echo "<pre class='" . ($key == ($line - 1) ? 'line' : '') . "'><code><span class='line-index'>" . ($key + 1) . "</span> " . htmlspecialchars( $value ) . "</code></pre>";
						}
					?>
                </div>
            </div>
        </div>
    </div>
</div>
