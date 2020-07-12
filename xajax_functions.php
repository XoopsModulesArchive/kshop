<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

function orderDetails($orderid){
global $order,$xoopsTpl,$xoopsUser;

require_once XOOPS_ROOT_PATH . '/class/template.php';
if (!isset($xoopsTpl)) {$xoopsTpl = new XoopsTpl();}
$xoopsTpl->xoops_setCaching(0);

//sanitize $orderid
$myts = myTextSanitizer::getInstance();
$oid=$myts->addslashes($orderid);

$odrprods=$order->loadallProdDB($oid);
$xoopsTpl->assign('odrprods', $odrprods);

$odrstat=$order->LoadcStat($oid);
$xoopsTpl->assign('odrstat', $odrstat);


$text = $xoopsTpl->fetch('db:ks_myorders_details.html');
//$text = 'hello world';

$objResponse = new xajaxResponse();
$objResponse->assign("detailsDiv","innerHTML",$text);
return $objResponse;

}

?>