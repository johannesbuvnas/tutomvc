<?php
namespace tutomvc;
?>
<form method="post" action="<?php menu_page_url( TutoMVCGitPage::NAME ); ?>">
	<input type="hidden" name="<?php echo GitPullFormMediator::ACTION_PULL; ?>" value="execute" />
	<?php submit_button( "Pull" ); ?>
</form>