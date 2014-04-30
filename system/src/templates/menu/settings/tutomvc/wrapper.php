<?php
namespace tutomvc;

	$facade = Facade::getInstance( Facade::KEY_SYSTEM );
	$parentPage = $facade->adminMenuPageCenter->get( TutoMVCSettingsPage::NAME );
	$navItems = array(
		$parentPage->getName() => $parentPage
	);

	foreach($parentPage->getSubpages() as $parentSubpage)
	{
		$navItems[ $parentSubpage->getName() ] = $parentSubpage;
	}
?>
<div class="wrap">
	<h2 class="nav-tab-wrapper">
		<?php
			foreach($navItems as $key => $page)
			{
				if($key == $currentPage->getName()) echo '<p class="nav-tab nav-tab-active">'.$page->getMenuTitle().'</p>';
				else echo '<a class="nav-tab" href="'.menu_page_url( $page->getMenuSlug(), FALSE ).'">'.$page->getMenuTitle().'</a>';
			}
		?>
	</h2>
	<?php
		if(!is_null( $contentMediator )) $contentMediator->render();
	?>
</div>
