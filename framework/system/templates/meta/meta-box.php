<?php
namespace tutons;
?>
<div class="MetaBox" data-meta-box-name="<?php echo $metaBox->getName(); ?>" data-cardinality-id="<?php echo $cardinalityID; ?>">

	<?php
		if( !$metaBox->isSingle() ):
	?>
	<div class="title"><span class="Label">#<?php echo $cardinalityID + 1; ?></span><a href="#" class="RemoveMetaBoxButton">Remove</a></div>
	<?php
		endif;
	?>

	<div class="MetaBoxInner">
		<table class="tutons">
			<tbody>
				<?php
					foreach( $metaFieldMap as $key => $metaVO )
					{
						$metaFieldMediator->setMetaField( $metaBox->getField( $key ) );
						$metaFieldMediator->parse( "metaVO", $metaVO );
						$metaFieldMediator->parse( "key", $key );
						$metaFieldMediator->render();
					}
				?>
			</tbody>
		</table>
	</div>

</div>