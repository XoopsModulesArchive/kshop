<?php
include '../../plugin_header.php';


$langfile=checkLang('payment','emailinvoice');
include($langfile);

$xoopsTpl->display(XOOPS_ROOT_PATH.'/modules/kshop/plugins/payment/emailinvoice/templates/emailinvoice.html');

?>