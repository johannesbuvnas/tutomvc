<?php
namespace tutomvc;

$facade = Facade::getInstance( Facade::KEY_SYSTEM );
$lines = file( $exception->getFile() );
?>
<link rel="stylesheet" href="<?php echo $facade->getURL( "assets/css/tutomvc.admin.css" ); ?>">

<div class="Debugger">
	<div class="Inner">
		<p>
			<span class="title"><?php echo $exception->getMessage(); ?></span><br/>
			<!-- <span class="description"><?php echo $exception->getFile(); ?></span> -->
		</p>
		<?php
			$i = 1;
			foreach($exception->getTrace() as $backtrace)
			{
				$lines = file( $backtrace['file'] );
		?>
				<div class="Backtrace">
					<div class="File">
						<span><?php echo $backtrace['file']; ?></span>
					</div>
					<div class="Code">
						<?php
							foreach($lines as $key => $value)
							{
								echo "<pre class='".($key == ($backtrace['line'] - 1) ? 'ExceptionLine' : '')."'><code><span class='LineNumber'>".($key + 1)."</span> ".htmlspecialchars($value)."</code></pre>";
							}
						?>
					</div>
				</div>
		<?php
				$i++;
				// Only backtrace one file
				break;
			}
		?>
	</div>
</div>