<?php
// +--------------------------------------------------------------------------+
// |							Kshop							      			
// | 							Module for Xoops	   					    
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com              
// +--------------------------------------------------------------------------+

require_once("header.php");

$xoopsOption['template_main'] = 'ks_prod_details.html';
include XOOPS_ROOT_PATH.'/header.php';




//-----------------------------------------------------------------------------
//-- If there's no product choosen, i.e. product_details is called without
//-- ?id=xx, go back to the startpage.
if (empty($_GET['id']))
{
    header("index.php");
    exit();
}


//-----------------------------------------------------------------------------
//-- Load item data and pass them to "$prod".
$myts = myTextSanitizer::getInstance();
$pid = $myts->addslashes($_GET['id']);
$prod = loadxProd($pid);
$xoopsTpl->assign('prod', $prod);

//-----------------------------------------------------------------------------
//-- Name of the item is shown as Title. Maybe this helps with search machines.
//-- No idea whether it helps, at least it looks good and doesn't hurt. ;)
$title=$xoopsModuleConfig['shopname'].' - '.$prod['name'];
$xoopsTpl->assign('xoops_pagetitle', $title);


//For Kshop Header
$xoopsTpl->assign('carttotal', BuildPrice($CART->chkwhichPrice()));
$xoopsTpl->assign('prodstotal', $CART->countItems());
$xoopsTpl->assign('shopname', $xoopsModuleConfig['shopname']);
//Check if user is allowed to checkout
if (chckifCheckout()) $xoopsTpl->assign('allowcheck', 1);



//Price
$xoopsTpl->assign('price', BuildPrice($prod['price']));
$xoopsTpl->assign('sprice', BuildPrice($prod['sp_price']));
if (($prod['has_special']==1) && ($prod['sp_price'] != "")) $xoopsTpl->assign('hasspec', 1);


//Options
if ($prod['has_style']==1){
$xoopsTpl->assign('options', prodOptions($_GET['id']));
}

include XOOPS_ROOT_PATH.'/include/comment_view.php';
include XOOPS_ROOT_PATH.'/footer.php';
?>