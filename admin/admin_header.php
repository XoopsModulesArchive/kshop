<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

require_once '../../../include/cp_header.php';

require_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/libclasses/lib.std.php';
require_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/libclasses/lib.shop.php';
require_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/libclasses/lib.html.php';
require_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/admin/functions.php';
require_once XOOPS_ROOT_PATH . '/class/template.php';
require("cp_tabs.php");


if (!isset($xoopsTpl)) {$xoopsTpl = new XoopsTpl();}
$xoopsTpl->xoops_setCaching(0);

$xoopsTpl->assign('xoops_dirname', $xoopsModule->getVar('dirname'));

?>