<?php
namespace tutomvc;
?>
<div class="wrap">
	<?php if($adminMenuPage->getMenuIconURL()): ?>
		<img src="<?php echo $adminMenuPage->getMenuIconURL(); ?>" />
	<?php endif; ?>
	<h2><?php echo $adminMenuPage->getPageTitle(); ?></h2>

	<?php
		$facade = Facade::getInstance( Facade::KEY_SYSTEM );
		foreach($facade->settingsCenter->getMap() as $settings)
		{
			if($settings->getMenuSlug() == $adminMenuPage->getMenuSlug())
			{
	?>
				<form method="post" action="options.php">
					<?php settings_fields( $settings->getName() ); ?>
					<?php do_settings_sections( $adminMenuPage->getMenuSlug() ); ?>
					<?php submit_button(); ?>
				</form>
	<?
			}
		}
	?>
</div>