<?php
	use tutomvc\wp\form\inputs\WPEditorFormInput;
	use tutomvc\wp\system\controller\actions\WPEditorAjaxCommand;

	/**
	 * @var WPEditorFormInput $formInput
	 */

	$nonce = wp_create_nonce( WPEditorAjaxCommand::NAME );
	$link  = admin_url( 'admin-ajax.php?action=' . WPEditorAjaxCommand::NAME . '&nonce=' . $nonce . '&elementName=' . htmlspecialchars( $formInput->getElementName() ) );
?>
<div class="wpeditor-placeholder" data-ajax-url="<?php echo $link; ?>" data-id="<?php echo $formInput->getID(); ?>">
    <div class="wpeditor-placeholder-container">
        <code>
			<?php
				$value = $formInput->getValue();
				if ( !empty( $value ) )
				{
					echo htmlspecialchars( $value );
				}
				else
				{
					echo "...";
				}
			?>
        </code>
    </div>
    <div class="wpeditor-placeholder-overlay">
        <span class="glyphicon glyphicon-pencil"></span>
    </div>
    <textarea class="hidden" name="<?php echo $formInput->getElementName(); ?>"><?php echo $formInput->getValue(); ?></textarea>
</div>
