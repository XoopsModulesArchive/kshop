<?php
// +--------------------------------------------------------------------------+
// |							Kshop							      			
// | 							Module for Xoops	   					    
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com              
// +--------------------------------------------------------------------------+

include 'header.php';
$config_handler =& xoops_gethandler('config');
$xoopsConfigSearch =& $config_handler->getConfigsByCat(XOOPS_CONF_SEARCH);

$xoopsOption['template_main'] = 'ks_search.html';
include XOOPS_ROOT_PATH.'/header.php';

if (strlen($_POST['search'])<$xoopsConfigSearch['keyword_min'])
{
$search=5;
redirect_header('index.php', 2, sprintf(KS_SEARCHTOOSML, $xoopsConfigSearch['keyword_min']));
die();
}


//Header
$xoopsTpl->assign('shopname', $xoopsModuleConfig['shopname']);
//For Kshop Header
$xoopsTpl->assign('carttotal', BuildPrice($CART->chkwhichPrice()));
$xoopsTpl->assign('prodstotal', $CART->countItems());
if (chckifCheckout()) $xoopsTpl->assign('allowcheck', 1);


$myts = myTextSanitizer::getInstance();
$search=$myts->addslashes($_POST['search']);

$sCat=srchCat($search);
if (!$sCat) $nocat=1;
$xoopsTpl->assign('nocat', $nocat);

$sProd=srchProd($search);
if (!$sProd) $noprod=1;
$xoopsTpl->assign('noprod', $noprod);


$xoopsTpl->assign('searchCats', $sCat);
$xoopsTpl->assign('searchProds', $sProd);
$xoopsTpl->assign('searchTerm', $_POST['search']);






//show Others Box
if ($xoopsModuleConfig['showspecial'])
{
$xoopsTpl->assign('incspecial', 1);
showSpecials();
}



include XOOPS_ROOT_PATH.'/footer.php';

?>