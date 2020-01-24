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
				var tutomvcParseMBURL = "<?php echo admin_url( 'admin-ajax.php?action=' . ParseMetaBoxAjaxCommand::NAME . '&nonce=' . $metaBoxNonce ); ?>";
            </script>
			<?php
		}
	}