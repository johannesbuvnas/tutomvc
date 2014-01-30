<?php
namespace tutomvc;

	$facade = Facade::getInstance( Facade::KEY_SYSTEM );
?>
<br/>
<div id="logsViewComponent" data-provider='<?php echo json_encode( $facade->logCenter->getMap() ); ?>' class="cf">
	<div class="Logs">
	</div>
</div>