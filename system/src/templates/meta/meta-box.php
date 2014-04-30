<?php
namespace tutomvc;
?>
<div class="MetaBox Loading" data-meta-box-name="<?php echo $metaBox->getName(); ?>" data-cardinality-id="<?php echo $cardinalityID; ?>">
	<?php
		if( !$metaBox->isSingle() ):
	?>
	<div class="title">
		<span class="Label">#<?php echo $cardinalityID + 1; ?></span>
		<a href="#" class="RemoveMetaBoxButton">Remove</a>
	</div>
	<?php
		endif;
	?>

	<div class="MetaBoxInner">
		<table class="tutomvc">
			<tbody>
				<?php
					foreach( $metaFieldMap as $key => $metaVO )
					{
				?>
					<tr>
						<td>
							<?php
								$metaFieldMediator->setMetaField( $metaBox->getField( $key ) );
								$metaFieldMediator->parse( "metaVO", $metaVO );
								$metaFieldMediator->parse( "key", $key );
								$metaFieldMediator->render();
							?>
						</td>
					</tr>
				<?php
					}
				?>
			</tbody>
		</table>
	</div>

</div>