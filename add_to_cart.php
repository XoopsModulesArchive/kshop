<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

include 'header.php';


//-- If no quantity has been set, the default quantity of 1 is used.
if (isset($_POST['qty'])) {
	$qty = $_POST['qty'];
} else{
	$qty = 1;
}


//check if product already exists
if (isset($CART->items[$_REQUEST['id']])){
	//Update quantity by adding new value to exisiting
	$CART->changeQuantity($_REQUEST['id'], $qty);
} else {
	//--Add item to cart
	$CART->addItem($_REQUEST['id'], $qty, $_REQUEST['opt_id']);
}

setcookie("kscart",serialize($CART),time()+3600);
$_SESSION['kshopcart'] = serialize($CART);

include XOOPS_ROOT_PATH.'/header.php';


header("Location: index.php");
exit;

include XOOPS_ROOT_PATH.'/footer.php';
?>