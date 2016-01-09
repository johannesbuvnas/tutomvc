<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 09/01/16
	 * Time: 11:55
	 * @var \tutomvc\wp\WPAttachmentFormInput $formInput
	 */
?>
<div class="list-group wp-attachment-list-group">
	<?php
		$values = (array)$formInput->getValue();
		foreach ( $values as $wpAttachmentID )
		{
			$src           = wp_get_attachment_image_src( $wpAttachmentID, "thumbnail", TRUE );
			$meta          = wp_get_attachment_metadata( $wpAttachmentID );
			$attachment    = wp_prepare_attachment_for_js( $wpAttachmentID );
			$attached_file = get_attached_file( $wpAttachmentID );

			if ( isset($meta[ 'filesize' ]) )
			{
				$bytes = $meta[ 'filesize' ];
			}
			elseif ( file_exists( $attached_file ) )
			{
				$bytes = filesize( $attached_file );
			}
			else
			{
				$bytes = '';
			}

			$bytesHumanReadable = size_format( $bytes );
			?>
			<div class="list-group-item">
				<div class="media">
					<div class="media-left">
						<img class="media-object" src="<?php echo $src[ 0 ]; ?>">
					</div>
					<div class="media-body">
						<h4 class="media-heading"><?php echo $attachment[ 'title' ]; ?></h4>
						<strong><?php _e( 'File name:' ); ?></strong> <?php echo $attachment[ 'filename' ]; ?>
						<strong><?php _e( 'File type:' ); ?></strong> <?php echo $attachment[ 'mime' ]; ?>
						<strong><?php _e( 'Uploaded on:' ); ?></strong> <?php echo $attachment[ 'dateFormatted' ]; ?>
						<strong><?php _e( 'File size:' ); ?></strong> <?php echo $bytesHumanReadable; ?>
						<?php if ( $attachment[ 'type' ] == "image" ): ?>
							<strong><?php _e( 'Dimensions:' ); ?></strong> <?php echo $attachment[ 'width' ] . "x" . $attachment[ 'height' ]; ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php
		}
	?>
</div>
