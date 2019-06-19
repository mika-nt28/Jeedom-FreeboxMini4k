<?php
require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
function FreeboxMini4k_install() {	
	exec("sudo chmod 755 /var/www/html/plugins/FreeboxMini4k/ressources/mini4k_cmd");
}
function FreeboxMini4k_update() {
	exec("sudo chmod 755 /var/www/html/plugins/FreeboxMini4k/ressources/mini4k_cmd");
}
function FreeboxMini4k_remove() {
}
?>
