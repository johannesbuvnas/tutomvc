<?php
namespace tutomvc;
?>
<div class="Backtrace">
	<div class="File">
		<span><?php echo $title; ?></span>
	</div>
	<div class="Code">
		<?php
			foreach($lines as $key => $value)
			{
				echo "<pre class='".($key == ($highlightedLine - 1) ? 'ExceptionLine' : '')."'><code><span class='LineNumber'>".($key + 1)."</span> ".htmlspecialchars($value)."</code></pre>";
			}
		?>
	</div>
</div>