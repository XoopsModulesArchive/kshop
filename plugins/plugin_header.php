<?php
include_once '../../../../../mainfile.php';

//Check if user is allowed to view this page.
if (!$xoopsUser->isAdmin()) {
        redirect_header('index.php', 2, _NOPERM);
		die;
    }


include XOOPS_ROOT_PATH.'/modules/kshop/libclasses/lib.html.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {$xoopsTpl = new XoopsTpl();}
$xoopsTpl->xoops_setCaching(0);

include_once XOOPS_ROOT_PATH . "/modules/kshop/libclasses/plugin.class.php";
$plugin= new pluginManager;

?>