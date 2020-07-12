<?php

include '../../plugin_header.php';


$langfile=checkLang('shipping','shipbyland');
include($langfile);

$xoopsTpl->display(XOOPS_ROOT_PATH.'/modules/kshop/plugins/shipping/shipbyland/templates/shipbyland.html');

?>