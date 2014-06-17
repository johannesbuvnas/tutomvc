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
				->prepareFile( $exception->getFile(), $exception->getLine(), TRUE )
				->render();

			$backtraced = -1;
			foreach(debug_backtrace() as $backtrace)
			{
				if($backtrace['file'] == $exception->getFile() || $backtraced > -1) $backtraced++;
				if($backtraced >= 1)
				{
					$codeMediator = $facade->view->getMediator( CodeMediator::NAME )
						->prepareFile( $backtrace['file'], $backtrace['line'], FALSE )
						->render();
				}
			}
		?>
	</div>
</div>