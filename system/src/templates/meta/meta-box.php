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
								$metaField = $metaBox->getField( $key );
								$metaFieldMediator->setMetaField( $metaField );
								$metaFieldMediator->parse( "metaVO", $metaVO );
								$metaFieldMediator->parse( "key", $key );
								if($metaField->getType() == MetaField::TYPE_SELECTOR_MULTIPLE && $metaField->getSetting( MetaField::SETTING_TAXONOMY )) $metaFieldMediator->parse( "elementClasses", array( "CustomTaxonomyMetaField" ) );
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