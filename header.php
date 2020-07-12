<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

include '../../mainfile.php';
include 'functions.php';

require_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/libclasses/user.class.php';
require_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/libclasses/lib.html.php';
require_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/libclasses/lib.shop.php';
require_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/libclasses/lib.std.php';
require_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/libclasses/order.class.php';
require_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/libclasses/sendmail.class.php';
require_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/libclasses/plugin.class.php';
require_once XOOPS_ROOT_PATH . '/modules/'.$xoopsModule->getVar('dirname').'/libclasses/cls.cart.php';


if (!empty($_SESSION['kshopuser'])) {
	$USER=unserialize($_SESSION['kshopuser']);
}else{
	$_SESSION['kshopuser'] = array();
}



if (isset($_SESSION['kshopcart'])) {
$CART = unserialize($_SESSION['kshopcart']);
} else {
$CART = new Cart();
}

if ((!isset($_SESSION['kshopcart'])) && (isset($_COOKIE['kscart']))){
$CART=unserialize($_COOKIE['kscart']);
}



?>