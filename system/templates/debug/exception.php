<?php
namespace tutomvc;

$facade = Facade::getInstance( Facade::KEY_SYSTEM );
?>
<link rel="stylesheet" href="<?php echo $facade->getURL( "assets/css/tutomvc.admin.css" ); ?>">

<div class="Debugger">
	<div class="Inner">
		<p>
			<span class="title"><?php echo $exception->getMessage(); ?></span>
		</p>
		<?php
			$codeMediator = $facade->view->getMediator( CodeMediator::NAME )
				->prepareFile( $exception->getFile(), $exception->getLine() )
				->render();
		?>
	</div>
</div>