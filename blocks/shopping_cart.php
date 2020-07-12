<?php

function shopping_cart (){
global $xoopsDB, $xoopsModuleConfig,$xoopsUser;

//Replace $modName with name you want for module
$modName='kshop';

require_once XOOPS_ROOT_PATH . '/modules/'.$modName.'/libclasses/lib.std.php';


$module_handler =& xoops_gethandler('module');
//Replace 'mymodule' with the name of your module
$module =& $module_handler->getByDirname($modName);
$config_handler =& xoops_gethandler('config');
$moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));


$min_order = $moduleConfig['minorder'];
$reqlogin =$moduleConfig['reqlogin'];

$showadd=1;
if ($reqlogin==1){
if (!is_object($xoopsUser)) {
$showadd=0;
}
}

if (!isset($_SESSION['shop_cart'])) {
$_SESSION['shop_cart'] = array();
$_SESSION['shop_cart']['items'] = array();
$_SESSION['shop_cart']['total'] = 0;
}

if ($_SESSION['shop_cart']['items']){
$prod =$_SESSION['shop_cart']['items'];
} else {
$prod ="0";
}
$cart = $_SESSION['shop_cart']['total'];

			if (($cart >= $min_order) && ($showadd==1)){
			$block['allowCheck'] = 1;
			} else {
			$block['allowCheck'] = 0;
			}
		
$cart = BuildPrice($cart);

$block['modname']=$modName;
$block['shop_prod']=$prod;
$block['shop_carttot']=$cart;

return $block;
}

?>