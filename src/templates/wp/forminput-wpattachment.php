<?php
	/**
	 * Created by PhpStorm.
	 * User: johannesbuvnas
	 * Date: 09/01/16
	 * Time: 11:55
	 * @var \tutomvc\wp\WPAttachmentFormInput $formInput
	 */
	$values = (array)$formInput->getValue();
?>
<div id="<?php echo $formInput->getID(); ?>" class="wpattachmentforminput" data-max="<?php echo $formInput->getMax(); ?>" data-title="<?php echo $formInput->getLabel(); ?>" data-button-text="<?php _e( "Add" ); ?>" data-type="<?php echo $formInput->getType(); ?>">
	<textarea class="hidden underscore-template">
		<div class="list-group-item" data-attachment-id="<%= id %>">
			<input type="hidden" name="<?php echo $formInput->getElementName(); ?>" value="<%= id %>">
			<div class="media" style="position:relative;">
				<div class="media-left" style="min-width: 150px;min-height: 150px; vertical-align: middle;cursor: move;">
					<img class="media-object" src="<%= src %>" style="margin: 0 auto;">
				</div>
				<div class="media-body">
					<h4 class="media-heading"><%= title %></h4>
					<ul>
						<li>
							<a href="<%= editLink %>"><?php _e( "Edit" ); ?></a> |
							<a role="button" href="#" class="btn-remove" data-target="#<?php echo $formInput->getID(); ?>"><?php _e( "Remove" ); ?></a>
						</li>
						<li>
							<strong><?php _e( 'Caption:' ); ?></strong>
							<em><%= caption %></em>
						</li>
						<li>
							<strong><?php _e( 'File name:' ); ?></strong> <%= filename %>
						</li>
						<li><strong><?php _e( 'File type:' ); ?></strong> <%= mime %>
						</li>
						<li>
							<strong><?php _e( 'Uploaded on:' ); ?></strong> <%= dateFormatted %>
						</li>
						<li><strong><?php _e( 'File size:' ); ?></strong> <%= filesizeHumanReadable %>
						</li>
						<% if ( type == "image" ) { %>
						<li>
							<strong><?php _e( 'Dimensions:' ); ?></strong> <%= width %>x<%= height %>
						</li>
						<% } %>
					</ul>
				</div>
			</div>
		</div>
	</textarea>
	<div class="list-group wp-attachment-list-group">
		<?php
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
				<div class="list-group-item" data-attachment-id="<?php echo $wpAttachmentID; ?>">
					<input type="hidden" name="<?php echo $formInput->getElementName(); ?>" value="<?php echo $wpAttachmentID; ?>">
					<div class="media" style="position:relative;">
						<div class="media-left" style="min-width: 150px;min-height: 150px; vertical-align: middle;cursor: move;">
							<img class="media-object" src="<?php echo $src[ 0 ]; ?>" style="margin: 0 auto;">
						</div>
						<div class="media-body">
							<h4 class="media-heading"><?php echo $attachment[ 'title' ]; ?></h4>
							<ul>
								<li>
									<a href="#"><?php _e( "Edit" ); ?></a> |
									<a role="button" href="#" class="btn-remove" data-target="#<?php echo $formInput->getID(); ?>"><?php _e( "Remove" ); ?></a>
								</li>
								<li>
									<strong><?php _e( 'Caption:' ); ?></strong>
									<em><?php echo $attachment[ 'caption' ]; ?></em>
								</li>
								<li>
									<strong><?php _e( 'File name:' ); ?></strong> <?php echo $attachment[ 'filename' ]; ?>
								</li>
								<li><strong><?php _e( 'File type:' ); ?></strong> <?php echo $attachment[ 'mime' ]; ?>
								</li>
								<li>
									<strong><?php _e( 'Uploaded on:' ); ?></strong> <?php echo $attachment[ 'dateFormatted' ]; ?>
								</li>
								<li><strong><?php _e( 'File size:' ); ?></strong> <?php echo $bytesHumanReadable; ?>
								</li>
								<?php if ( $attachment[ 'type' ] == "image" && (isset($attachment[ 'width' ]) && isset($attachment[ 'height' ])) ): ?>
									<li>
										<strong><?php _e( 'Dimensions:' ); ?></strong> <?php echo $attachment[ 'width' ] . "x" . $attachment[ 'height' ]; ?>
									</li>
								<?php endif; ?>
							</ul>
						</div>
					</div>
				</div>
				<?php
			}
		?>
	</div>
	<button type="button" class="btn btn-default btn-add" data-target="#<?php echo $formInput->getID(); ?>" disabled style="margin: 0 auto;display: block">
		<span class="glyphicon glyphicon-plus"></span>
	</button>
</div>
<script type="text/javascript">
	jQuery( document ).ready( function ()
	{
		jQuery( "#<?php echo $formInput->getID(); ?>" ).sortable( {
			items: ".list-group-item",
			opacity: 0.5,
			handle: ".media-left",
			tolerance: "pointer"
		} );
	} );
</script>