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
		/** @var PostMetaBox $formGroup */
		$formGroup     = new ExampleFissileFormGroup();
		$formGroupData = array();

		// save_post action
		if ( is_array( $_POST ) && count( $_POST ) )
		{
//			foreach ( $_POST[ ExampleFissileFormGroup::NAME ] as $data )
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
			if ( array_key_exists( $formGroup->getName(), $_POST ) )
			{
				$formGroupData = $_POST[ $formGroup->getName() ];
				$formGroupData = array_map( 'stripslashes_deep', $formGroupData );
				$formGroup->setValue( $formGroupData );
//				var_dump( $formGroupData );
//				exit;
			}
		}
		else
		{
			$formGroup->setValue( $formGroup->getValueMapAt() );
		}
	?>
	<form method="post" name="my_form">
		<div class="row">
			<div class="col-xs-5">

				<?php
					//					$formGroup->setIndex( 50 );
					echo $formGroup->getElement();
				?>

			</div>
			<div class="col-xs-6">
				<?php submit_button( "Submit", array("button", "primary", "button-large") ); ?>
			<pre>
			<?php
				echo "]<br/><p><strong>GET VALUE MAP AT: NULL</strong></p>";
				print_r( $formGroup->getValueMapAt() );
				echo "]<br/><p><strong>FLAT VALUE:</strong></p>";
				print_r( $formGroup->getFlatValue() );
				echo "]<br/><p><strong>GET VALUE MAP BY: form_gr()oup[0][default]</strong></p>";
				print_r( $formGroup->getValueMapByElementName( "form_gr()oup[0][default]" ) );
				echo "]<br/><p><strong>GET VALUE MAP BY: form_group[10]</strong></p>";
				print_r( $formGroup->getValueMapByElementName( "form_group[10]" ) );
				echo "]<br/><p><strong>GET VALUE MAP BY: form_group</strong></p>";
				print_r( $formGroup->getValueMapByElementName( "form_group" ) );
				//				if ( !empty($_POST) )
				{

//					print_r( $_POST );
//					print_r( $formGroup->toMetaKeyVO( array_pop( $formGroup->getValue() ) ) );
				}
			?>
				</pre>
			</div>
		</div>
	</form>

</div>
