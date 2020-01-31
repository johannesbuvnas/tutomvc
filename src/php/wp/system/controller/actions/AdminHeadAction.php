<?php

	namespace tutomvc\wp\system\controller\actions;

	use tutomvc\wp\core\controller\command\ActionCommand;

	class AdminHeadAction extends ActionCommand
	{
		public function execute()
		{
			$parseFormGroupNonce = wp_create_nonce( ParseFormGroupAjaxCommand::NAME );
			?>
            <script type="text/javascript">
				var TutoMVC = {
					parseURL: "<?php echo admin_url( 'admin-ajax.php?action=' . ParseFormGroupAjaxCommand::NAME . '&nonce=' . $parseFormGroupNonce ); ?>",
					parseNonce: "<?php echo $parseFormGroupNonce; ?>",
					parseAction: "<?php echo ParseFormGroupAjaxCommand::NAME ?>"
				};
            </script>
			<?php
		}
	}