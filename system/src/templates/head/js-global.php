<?php
namespace tutomvc;
$screen = get_current_screen();
?>
<script type="text/javascript">
	window.TutoMVC = {
		constructURL : function(baseURL, relativePath)
		{
			return relativePath ? baseURL + "/" + relativePath : baseURL;
		},
		getURL : function(relativePath)
		{
			return this.constructURL( "<?php echo TutoMVC::getURL(); ?>", relativePath );
		},
		getAdminURL : function(relativePath)
		{
			return this.constructURL( "<?php echo admin_url(); ?>", relativePath );
		},
		version : "<?php echo TutoMVC::VERSION; ?>",
		nonce : "<?php echo wp_create_nonce( TutoMVC::NONCE_NAME ); ?>",
		ajaxURL : "<?php echo admin_url( 'admin-ajax.php' ); ?>",
		currentScreenID : "<?php echo $screen->id; ?>"
	};
</script>