<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

include 'admin_header.php';

include_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/libclasses/order.class.php';
require (XOOPS_ROOT_PATH .'/modules/'.$xoopsModule->getVar('dirname').'/libclasses/xajax/xajax_core/xajax.inc.php');
require (XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/admin/xajax_admin.php');

$order= new Order();


$xajax = new xajax(); 
if ($xoopsModuleConfig['debug']==1) $xajax->setFlag("debug", true);
$xajax->registerFunction("addStatus");
$xajax->processRequest();


xoops_cp_header();


$Xjavapath=XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/libclasses/xajax';
$xajaxjava=$xajax->getJavascript($Xjavapath);
$xoopsTpl->assign('xajaxjava', $xajaxjava);


$mainTabs->setCurrent('orders', 'tabs');
$mainTabs->display();




//Add Status
if (isset($_POST['addNew'])){
$order->saveorderStatus($_POST['oid'],$_POST['status'],$_POST['comment']);
$order->sendStatusMail($_POST['oid']);
}

//del order
if (isset($_POST['delOrder'])){
if (isset($_POST['erasebox'])) $delorder=$order->eraseOrder($_POST['erasebox']);
if (!$delorder) echo "error";
}

$orders=$order->loadAllOrders();
$xoopsTpl->assign('orders', $orders);


$xoopsTpl->display('db:admin_orders.html');

xoops_cp_footer();

?>