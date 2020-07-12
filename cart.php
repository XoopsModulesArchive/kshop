<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+


include 'header.php';
$xoopsOption['template_main'] = 'ks_cart.html';


if (isset($_POST['recalc_x'])){
	updateQty($_POST);
}


if (isset($_REQUEST['func']))
{
	switch ($_REQUEST['func'])
	{
		case "remove" : $CART->deleteItem($_REQUEST['id']);
		break;

		case "empty" :  $CART->init();
		break;
	}
}

include XOOPS_ROOT_PATH.'/header.php';

cartTemplate();


function updateQty($form)
{
	global $CART;

	foreach ($form['id'] as $i => $pid)
	{
		$qty = $form['qty'][$i];
		$CART->changeQuantity($pid, $qty);
	}
}

//For Kshop Header
$xoopsTpl->assign('carttotal', BuildPrice($CART->chkwhichPrice()));
$xoopsTpl->assign('prodstotal', $CART->countItems());
$xoopsTpl->assign('shopname', $xoopsModuleConfig['shopname']);
if (chckifCheckout()) $xoopsTpl->assign('allowcheck', 1);



setcookie("kscart",serialize($CART),time()+3600,'/',XOOPS_URL,0);
$_SESSION['kshopcart'] = serialize($CART);
include XOOPS_ROOT_PATH.'/footer.php';
?>