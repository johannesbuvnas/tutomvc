<style type="text/css">
    .code-wrapper
    {
        width: 100%;
        background: #fff;
        overflow: scroll;
        color: #666;
        margin-bottom: 10px;
    }

    .code-wrapper code
    {
        font-size: 10px;
        background: 0;
        padding: 0;
        margin: 0;
        line-height: 1.2em;
    }

    .code-wrapper pre
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


    /* end .tutomvc-log-handler */
</style>
<div class="wrap">
    <div class="container-fluid">
        <h1>Logs</h1>
        <script type="text/javascript">
			jQuery( document ).ready( function ( $ ) {
				var $form = $( "#logs" );
				var $datepicker = $( "#logs .datepicker,#logs .sitepicker" );
				$datepicker.on( "change", function () {
					console.log( "change" );
					$.ajax( {
						type: $form.attr( "method" ),
						url: $form.attr( "action" ),
						data: $form.serialize(), // serializes the form's elements.
						success: function ( result ) {
							$( ".code-wrapper" ).html( "<pre><code>" + result + "</code></pre>" );
						},
						error: function ( result ) {
							console.error( result );
						}
					} );
				} );
				$datepicker.trigger( "change" );
			} );
        </script>
        <div class="panel panel-default">
            <div class="panel-heading">
                <form id="logs" method="get" action="<?php echo admin_url( 'admin-ajax.php' ); ?>">
                    <input type="hidden" name="action" value="<?php echo \TutoMVC\WordPress\Modules\Log\Controller\Ajax\GetLogAjaxCommand::NAME; ?>"/>
                    <input type="hidden" name="nonce" value="<?php echo wp_create_nonce( \TutoMVC\WordPress\Modules\Log\Controller\Ajax\GetLogAjaxCommand::NAME ); ?>"/>
                    <input type="date" class="datepicker form-control" name="date" value="<?php echo date( "Y-m-d" ); ?>"/>
					<?php
						if ( is_multisite() && is_network_admin() )
						{
							?>
                            <select class="sitepicker" name="site">
								<?php
									$sites = get_sites();
									foreach ( $sites as $site )
									{
										?>
                                        <option <?php if ( get_current_blog_id() == $site[ 'blog_id' ] ) echo "selected"; ?> value="<?php echo $site[ 'blog_id' ]; ?>"><?php echo $site[ 'domain' ] ?></option>
										<?php
									}
								?>
                            </select>
							<?php
						}
					?>
                </form>
            </div>
            <div class="panel-body">
                <div class="code-wrapper">
                </div>
            </div>
        </div>
    </div>
</div>
