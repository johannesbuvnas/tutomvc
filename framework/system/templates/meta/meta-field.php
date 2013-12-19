<!-- <tr>
	<th>
		<label for="<?php echo $metaVO->getName(); ?>[]">
			<?php echo $metaField->getTitle(); ?>
			<br/>
			<span class="description"><?php echo $metaField->getDescription(); ?></span>
		</label>
	</th>
</tr> -->
<tr>
	<td>
		<?php 
			$inputMediator->parse( "metaVO", $metaVO );
			$inputMediator->parse( "metaField", $metaField );
			$inputMediator->render();
		?>
	</td>
</tr>