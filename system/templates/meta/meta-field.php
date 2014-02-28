<?php
namespace tutomvc;

			if($metaField->getType() == MetaField::TYPE_TEXTAREA_WYSIWYG)
			{
				// Textarea value is set when the editor is requested from AJAX
				// $value = GetMetaDatFilter::getDBMetaValue( $metaVO->getPostID(), $metaVO->getName() );
				// $value = is_array($value) && count($value) ? $value[0] : $value;
				$value = "";
			}
			else
			{
				$value = $metaVO->getValue();
			}

			if( is_string($value) ) $value = base64_encode( $value );

			// if( count( $value ) == 1 && is_string( $value[0] ) ) $value = base64_encode( $value[0] );
			// else if( count( $value ) == 0 ) $value = "";
			
			$vo = array();
			$vo['postID'] = $metaVO->getPostID();
			$vo['title'] = $metaField->getTitle();
			$vo['description'] = $metaField->getDescription();
			$vo['name'] = $metaField->getName();
			$vo['key'] = $metaVO->getName();
			$vo['value'] = $value;
			$vo['conditions'] = array();
			foreach($metaField->getConditions() as $metaCondition) $vo['conditions'][] = $metaCondition->toArray();
			$vo['type'] = array
			(
				"name" => $metaVO->getType(),
				"settings" => $metaVO->getSettings()
			);
		?>

		<div class="MetaField <?php echo isset( $elementClasses ) ? implode( " ", $elementClasses ) : ""; ?>">
			<p class="MetaFieldHeader">
				<label for="<?php echo $metaVO->getName(); ?>[]">
					<span class="title"><?php echo $metaField->getTitle(); ?></span>
					<br/>
					<span class="description"><?php echo $metaField->getDescription(); ?></span>
				</label>
			</p>

			<div class="JSON">
				<?php echo json_encode( $vo, JSON_HEX_QUOT | JSON_HEX_TAG ); ?>
			</div>
		</div>