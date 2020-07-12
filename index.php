<?php
// +--------------------------------------------------------------------------+
// |							Kshop							      			
// | 							Module for Xoops	   					    
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com              
// +--------------------------------------------------------------------------+

include 'header.php';


$xoopsOption['template_main'] = 'ks_main_body.html';
include XOOPS_ROOT_PATH.'/header.php';


//sanitize category id
$myts = myTextSanitizer::getInstance();
(isset($_GET['id'])) ? $catid = $myts->addslashes($_GET['id']) : $catid=1;


if (isset($_POST['mybrand'])){
//sanitize brand id
$brand=$myts->addslashes($_POST['brands']);
//load all products in brand
$prods=loadProdinBrand($brand);
} else {
$prods=ldprodperCat($catid);
}

if (!empty($prods))
{
$selcat=loadCat($catid);
$xoopsTpl->assign('selcat', $selcat);
$xoopsTpl->assign('prods', $prods);
$xoopsTpl->assign('colnum', $xoopsModuleConfig['colpercat']);
$xoopsTpl->assign('has_prod', 1);
} else {
$xoopsTpl->assign('has_prod', 0);
}

//For Kshop Header
$xoopsTpl->assign('carttotal', BuildPrice($CART->chkwhichPrice()));
$xoopsTpl->assign('prodstotal', $CART->countItems());
if (chckifCheckout()) $xoopsTpl->assign('allowcheck', 1);

//Header
$xoopsTpl->assign('shopname', $xoopsModuleConfig['shopname']);


if ($catid==1) {
$xoopsTpl->assign('inchome', '1');
$xoopsTpl->assign('textbox', textBox(1));
//Page title. Display slogan for home page only
$title=$xoopsModuleConfig['shopname']." - ".$xoopsModuleConfig['slogan'];
$xoopsTpl->assign('xoops_pagetitle', $title);
} else {
$xoopsTpl->assign('inchome', '0');
}
if (isset($brand)) $xoopsTpl->assign('inchome', '0');


if (isset($_GET['area'])) {
$area=$myts->addslashes($_GET['area']);
$xoopsTpl->assign('dontshowNO', 1);
} else {
$area='';
}
$xoopsTpl->assign('textbox', textBox($area));


//show Others Box
if ($xoopsModuleConfig['showspecial'])
{
$xoopsTpl->assign('incspecial', 1);
showSpecials();
}

include XOOPS_ROOT_PATH.'/footer.php';

?>