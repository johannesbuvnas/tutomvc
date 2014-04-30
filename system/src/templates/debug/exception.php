<?php
namespace tutomvc;
$facade = Facade::getInstance( Facade::KEY_SYSTEM );
?>
<div class="Debugger">
	<div class="Inner">
		<h1>Tuto MVC</h1>
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