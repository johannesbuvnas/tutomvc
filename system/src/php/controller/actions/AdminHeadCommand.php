<?php
namespace tutons;

class AdminHeadCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct( "admin_head" );
	}

	function execute()
	{
		$globals = array(
			"nonce" => wp_create_nonce( TutoFramework::NONCE_ID ),
			"baseURL" => $this->getFacade()->getURL(),
			"ajaxURL" => admin_url( 'admin-ajax.php' )
		);

		echo '
			<script type="text/javascript">
				Tuto = '.stripslashes( json_encode( $globals ) ).';
			</script>
			<script type="text/javascript" src="http://local.rkivo.com/_wp/wp-includes/js/tinymce/wp-tinymce.php?c=1&ver=359-20131026"></script>
		';

		// echo '<script type="text/javascript" data-main="'.$this->getFacade()->getURL( "src/js/Main.config.js" ).'" src="'.$this->getFacade()->getURL( "libs/js/require.js" ).'"></script>';
	}
}