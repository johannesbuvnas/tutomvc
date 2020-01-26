<?php

	namespace tutomvc\wp\metabox;

	use tutomvc\wp\core\controller\command\ActionCommand;
	use function admin_url;
	use function wp_create_nonce;

	class AdminHeadAction extends ActionCommand
	{
		public function execute()
		{
			$metaBoxNonce = wp_create_nonce( ParseMetaBoxAjaxCommand::NAME );
			?>
            <script type="text/javascript">
				var TutoMVCMetaBoxModule = {
					parseURL: "<?php echo admin_url( 'admin-ajax.php?action=' . ParseMetaBoxAjaxCommand::NAME . '&nonce=' . $metaBoxNonce ); ?>",
					parseNonce: "<?php echo $metaBoxNonce; ?>",
					parseAction: "<?php echo ParseMetaBoxAjaxCommand::NAME ?>"
				};
            </script>
			<?php
		}
	}