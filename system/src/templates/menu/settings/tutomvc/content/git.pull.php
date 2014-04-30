<?php
  namespace tutomvc;
  $facade = Facade::getInstance( Facade::KEY_SYSTEM );
?>

<form method="post">
  <input type="hidden" name="<?php echo ActionCommand::GIT_PULL; ?>" value="<?php echo wp_create_nonce( ActionCommand::GIT_PULL ); ?>" />
  <?php
    submit_button( "Pull", "primary" );
  ?>
</form>
