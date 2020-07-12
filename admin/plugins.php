<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            	www.kaotik.biz
// |            	kaotik1@gmail.com
// +--------------------------------------------------------------------------+

include 'admin_header.php';

//Check if user is allowed to view this page
checkPerm(1);


require (XOOPS_ROOT_PATH .'/modules/'.$xoopsModule->getVar('dirname').'/libclasses/xajax/xajax_core/xajax.inc.php');
require (XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/admin/xajax_admin.php');
include_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/libclasses/plugin.class.php';

$plugin= new pluginManager;

$xajax = new xajax(); 
if ($xoopsModuleConfig['debug']==1) $xajax->setFlag("debug", true);
$xajax->registerFunction("plugType");
$xajax->registerFunction("Xinstaplug");
$xajax->registerFunction("Xunplug");
$xajax->processRequest();

xoops_cp_header();

$Xjavapath=XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/libclasses/xajax';
$xajaxjava=$xajax->getJavascript($Xjavapath);
$xoopsTpl->assign('xajaxjava', $xajaxjava);

$mainTabs->setCurrent('plugins', 'tabs');
$mainTabs->display();

$xoopsTpl->assign('plugs', $plugin->InstalledPlugs('payment'));
$xoopsTpl->assign('avails', $plugin->CheckAvailPlug('payment'));

$xoopsTpl->display('db:admin_plugins.html');


xoops_cp_footer();
?>