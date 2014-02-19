<?php
namespace tutomvc;
?>
<script type="text/javascript">
	window.TutoMVC = {
		getURL : function(relativePath)
		{
			var url = "<?php echo TutoMVC::getURL(); ?>";
			return relativePath ? url + "/" + relativePath : url;
		},
		version : "<?php echo TutoMVC::VERSION; ?>",
		nonce : "<?php echo wp_create_nonce( TutoMVC::NONCE_NAME ); ?>",
		ajaxURL : "<?php echo admin_url( 'admin-ajax.php' ); ?>"
	};
</script>