<div class="wrap">
    <div class="container-fluid">
        <h1>Logs</h1>
        <form id="logs" method="get" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">
            <input type="hidden" name="action" value="<?php echo \tutomvc\wp\log\actions\GetLogAjaxCommand::NAME; ?>"/>
            <input type="hidden" name="nonce" value="<?php echo wp_create_nonce( \tutomvc\wp\log\actions\GetLogAjaxCommand::NAME ); ?>"/>
            <input type="date" class="datepicker" name="date"/>
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
                            $( ".card-log" ).removeClass( "hidden" ).html( "<pre><code>" + result + "</code></pre>" );
                        },
                        error: function ( result ) {
                            console.error( result );
                        }
                    } );
                } );
            } );
        </script>
        <div class="card card-log hidden">
            <div class="card-block">
            </div>
        </div>
    </div>
</div>
