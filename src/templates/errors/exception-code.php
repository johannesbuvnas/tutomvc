<?php
	/** @var string $filePath */
	/** @var int $line */
	/** @var bool $expanded */
	$lines = array();
	if ( is_file( $filePath ) )
	{
		$lines = file( $filePath );
	}
?>
<div class="backtrace <?php if ( !$expanded ) echo "collapsed"; ?>">
	<div class="title">
		<span class="minus">-</span><span class="plus">+</span><span><?php echo $filePath; ?></span>
	</div>
	<div class="code-wrapper">
		<?php
			foreach ( $lines as $key => $value )
			{
				echo "<pre class='" . ($key == ($line - 1) ? 'line' : '') . "'><code><span class='line-index'>" . ($key + 1) . "</span> " . htmlspecialchars( $value ) . "</code></pre>";
			}
		?>
	</div>
</div>