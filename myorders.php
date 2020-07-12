<?php
// +--------------------------------------------------------------------------+
// |							Kshop							      			
// | 							Module for Xoops	   					    
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com              
// +--------------------------------------------------------------------------+

include 'header.php';
$order= new Order();

include 'xajax_functions.php';
require (XOOPS_ROOT_PATH .'/modules/'.$xoopsModule->getVar('dirname').'/libclasses/xajax/xajax_core/xajax.inc.php');
$xajax = new xajax(); 
if ($xoopsModuleConfig['debug']==1) $xajax->setFlag("debug", true);
$xajax->registerFunction("orderDetails");
$xajax->processRequest();

$xoopsOption['template_main'] = 'ks_myorders.html';
include XOOPS_ROOT_PATH.'/header.php';

$Xjavapath=XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/libclasses/xajax';
$xajaxjava=$xajax->getJavascript($Xjavapath);
$xoopsTpl->assign('xajaxjava', $xajaxjava);


$myorder=$order->loadAllOrders($xoopsUser->getVar('uid'));
$xoopsTpl->assign('orders', $myorder);


//Header
$xoopsTpl->assign('shopname', $xoopsModuleConfig['shopname']);

//For Kshop Header
$xoopsTpl->assign('carttotal', BuildPrice($CART->chkwhichPrice()));
$xoopsTpl->assign('prodstotal', $CART->countItems());
if (chckifCheckout()) $xoopsTpl->assign('allowcheck', 1);





include XOOPS_ROOT_PATH.'/footer.php';

?>