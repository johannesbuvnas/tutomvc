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

$vo = array();
$vo['postID'] = $metaVO->getPostID();
$vo['title'] = $metaField->getTitle();
$vo['description'] = $metaField->getDescription();
$vo['metaFieldName'] = $metaField->getName();
$vo['name'] = $metaVO->getName();
$vo['value'] = $value;
$vo['conditions'] = array();
if(is_array($metaField->getConditions()) && count($metaField->getConditions())) foreach($metaField->getConditions() as $metaCondition) $vo['conditions'][] = $metaCondition->toArray();
$vo['type'] = $metaVO->getType();
$vo = is_array($metaVO->getSettings()) ? array_merge( $metaVO->getSettings(), $vo ) : $vo;
?>

<div class="MetaField <?php echo isset( $elementClasses ) ? implode( " ", $elementClasses ) : ""; ?>">
	<div class="Input">
		<?php
			// Fallback solution if JavaScript failes
			// These input values will get replaced by JavaScript
			$rawValues = $metaVO->getDBValue();

			if(!is_array($rawValues)) $rawValues = array();

			foreach( $rawValues as $key => $rawValue )
			{	
				if(is_string($rawValue) )
				{
		?>
					<textarea class="HiddenElement" name="<?php echo $metaVO->getName() ?>[]"><?php echo $rawValue; ?></textarea>
		<?php
				}
			}
		?>
	</div>
	<textarea class="JSON">
		<?php echo json_encode( $vo, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP ); ?>
	</textarea>
</div>