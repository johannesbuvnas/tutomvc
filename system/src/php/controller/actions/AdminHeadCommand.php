<?php
namespace tutomvc;

class AdminHeadCommand extends ActionCommand
{
	function __construct()
	{
		parent::__construct( "admin_head" );
	}

	function execute()
	{
		$globals = array(
			"nonce" => wp_create_nonce( TutoMVC::NONCE_NAME ),
			"baseURL" => $this->getFacade()->getURL(),
			"ajaxURL" => admin_url( 'admin-ajax.php' )
		);

		echo '
			<script type="text/javascript">
				Tuto = '.stripslashes( json_encode( $globals ) ).';
			</script>
		';
	}
}