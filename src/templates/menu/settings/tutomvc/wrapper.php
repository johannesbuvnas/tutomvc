<?php
	namespace tutomvc;

	$facade     = Facade::getInstance( Facade::KEY_SYSTEM );
	$parentPage = $facade->adminMenuPageCenter->get( TutoMVCSettingsPage::NAME );
	$navItems   = array(
		$parentPage->getName() => $parentPage
	);

	foreach ( $parentPage->getSubpages() as $parentSubpage )
	{
		$navItems[ $parentSubpage->getName() ] = $parentSubpage;
	}
?>
<div class="wrap">
	<h2 class="nav-tab-wrapper">
		<?php
			foreach ( $navItems as $key => $page )
			{
				if ( $key == $currentPage->getName() ) echo '<p class="nav-tab nav-tab-active">' . $page->getMenuTitle() . '</p>';
				else echo '<a class="nav-tab" href="' . menu_page_url( $page->getMenuSlug(), FALSE ) . '">' . $page->getMenuTitle() . '</a>';
			}
		?>
	</h2>
	<?php
		if ( !is_null( $contentMediator ) ) $contentMediator->render();
		if ( is_array( $_POST ) && count( $_POST ) ) var_dump( $_POST );
		$formGroup = new TestFormGroup();
	?>
	<form method="post" class="col-xs-6">
		<ul class="list-group metabox-list-group" data-max-cardinality="-1" data-min-cardinality="0">
			<li class="list-group-item">
				<h3>
					<?php
						echo $formGroup->getTitle();
					?>
					<small class="help-block">
						<?php
							echo $formGroup->getDescription();
						?>
					</small>
				</h3>
			</li>
			<li class="list-group-item disabled">
				<select class="selectpicker" data-width="auto">
					<option selected>#1</option>
					<option>#2</option>
				</select>
				<a class="btn btn-sm btn-danger pull-right" href="#"><span class="glyphicon glyphicon-remove"></span></a>
			</li>
			<li class="list-group-item metabox-item">
				<?php
					echo $formGroup->getFormElement();
				?>
			</li>
			<li class="list-group-item disabled">
				<select class="selectpicker" data-width="auto">
					<option>#1</option>
					<option selected>#2</option>
				</select>
				<a class="btn btn-sm btn-danger pull-right" href="#"><span class="glyphicon glyphicon-remove"></span></a>
			</li>
			<li class="list-group-item">
				<?php
					echo $formGroup->getFormElement();
				?>
			</li>
			<li class="list-group-item" style="text-align: center">
				<a class="btn btn-primary" href="#"><span class="glyphicon glyphicon-plus"></span></a>
			</li>
		</ul>
		<?php
			submit_button( "Submit" );
		?>
	</form>
</div>
