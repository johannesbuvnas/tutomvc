<?php
  namespace tutomvc;
  $facade = Facade::getInstance( Facade::KEY_SYSTEM );
?>

<form method="post">
  <input type="hidden" name="<?php echo ActionCommand::GIT_ADD; ?>" value="<?php echo wp_create_nonce( ActionCommand::GIT_ADD ); ?>" />
  <?php
    submit_button( "Add", "primary" );
  ?>
</form>
