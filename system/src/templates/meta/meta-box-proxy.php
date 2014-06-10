<?php
namespace tutomvc;
wp_nonce_field( $metaBox->getName(), $metaBox->getName() . "_nonce" );

// JS Backbone.Model
$model = array();
$model['conditions'] = array();
$model['name'] = $metaBox->getName();
$model['maxCardinality'] = $metaBox->getMaxCardinality();
$model['metaBoxMap'] = (object)array();
foreach($metaBox->getConditions() as $metaCondition) $model['conditions'][] = $metaCondition->toArray();
?>
<div class="MetaBoxModel" data-post-id="<?php echo $postID; ?>" data-meta-box-name="<?php echo $metaBox->getName(); ?>" data-max-cardinality="<?php echo $metaBox->getMaxCardinality(); ?>">
	<?php
		echo '<input type="hidden" id="'.$metaBox->getName().'" name="'.$metaBox->getName().'" value="'.$metaBox->getCardinality( $postID ).'" />';
	?>
	<div class="JSON MetaBoxAttributes">
		<?php echo json_encode( $model, JSON_HEX_QUOT | JSON_HEX_TAG ); ?>
	</div>
	<div class="MetaBoxProxy">
		<?php
			foreach( $metaBox->getMetaBoxMap( $postID ) as $cardinalityID => $metaFieldMap )
			{
				$metaBoxMediator->parse( "cardinalityID", $cardinalityID );
				$metaBoxMediator->parse( "metaFieldMap", $metaFieldMap );
				$metaBoxMediator->render();
			}
		?>
	</div>
	<div class="HiddenElement MetaBoxDummy">
		<?php
			$metaBoxMediator->parse( "cardinalityID", 0 );
			$metaBoxMediator->parse( "metaFieldMap", $metaBox->getMetaFieldMap( 0, 0 ) );
			$metaBoxMediator->render();
		?>
	</div>
	<div class="Button AddMetaBoxButton">
		<span class="Label">Add</span>
	</div>
</div>