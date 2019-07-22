<?php
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
?>
<form class="form-horizontal">
	<fieldset>	
		<div class="form-group">
			<label class="col-md-2 control-label" >{{Pause dans la boucle du demon (s)}}</label>
			<div class="col-md-3">
				<input type="text" class="configKey form-control" data-l1key="DemonSleep" />
			</div>
		</div>
	
	</fieldset> 
</form>
