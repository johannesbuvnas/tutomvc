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
		$formGroup     = new TestFormGroup();
		$formGroupData = array();

		// save_post action
		if ( is_array( $_POST ) && count( $_POST ) )
		{
//			foreach ( $_POST[ TestFormGroup::NAME ] as $data )
//			{
//				$index = intval( $data[ "#" ] );
//				unset($data[ "#" ]);
//				while( array_key_exists( $index, $formGroupData ) )
//				{
//					// If index already exists, up it
//					$index ++;
//				}
//				$formGroupData[ $index ] = $data;
//			}
//			ksort( $formGroupData );
//			$formGroupData = array_combine( range( 0, count( $formGroupData ) - 1 ), array_values( $formGroupData ) );
			if(array_key_exists($formGroup->getName(), $_POST ))
			{
				$formGroupData = $_POST[ $formGroup->getName() ];
				$formGroup->setValue( $formGroupData );
			}
		}
	?>
	<form method="post">
		<div class="row">
			<div class="col-xs-5">

				<?php
					echo $formGroup->getFormElement();
				?>

			</div>
			<div class="col-xs-6">
			<pre>
			<?php if ( !empty($_POST) )
			{
				print_r( $formGroup->getValue() );
				print_r( $_POST );
			} ?>
				</pre>
				<?php
					submit_button( "Submit" );
				?>
			</div>
		</div>
	</form>

</div>
