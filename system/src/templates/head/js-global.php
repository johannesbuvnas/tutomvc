<?php
namespace tutomvc;
$screen = get_current_screen();
global $current_user;
global $post;
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
		isDevelopmentMode :  <?php echo SystemFacade::DEVELOPMENT_MODE ? 1 : 0; ?>,
		version : "<?php echo TutoMVC::VERSION; ?>",
		nonce : "<?php echo wp_create_nonce( TutoMVC::NONCE_NAME ); ?>",
		ajaxURL : "<?php echo admin_url( 'admin-ajax.php' ); ?>",
		currentScreenID : "<?php echo $screen->id; ?>",
		currentUser : <?php echo json_encode( $current_user ); ?>,
		post : <?php echo json_encode( $post ); ?>,
		metaFieldGoogleMapsAPIKey : "<?php echo MetaField::$GOOGLE_MAPS_API_KEY; ?>"
	};
</script>