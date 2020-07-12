<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

global $xoopsModule;
require_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/libclasses/tabs.php';

/* set up our main tabs */
$mainTabs = new XoopsTabs();
$mainTabs->_style='ktabs';

// tab for main index
$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/index.php';
$mainTabs->addTab( 'index', $link, KS_TABS_MAIN, 0 );

$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/products.php';
$mainTabs->addTab( 'products', $link,KS_TABS_PROD, 10 );

 //subtab Products
	$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/products.php';
	$mainTabs->addSub( 'prodmain', $link,KS_SUBTABS_MAIN, 10, 'products');
	
// subtab Product Options
	$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/products_options.php';
	$mainTabs->addSub( 'prodoptions', $link,KS_SUBTABS_PRODOPTIONS, 20, 'products');
	
// subtab Product Brands
	$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/products_brands.php';
	$mainTabs->addSub( 'prodbrands', $link,KS_SUBTABS_PRODBRANDS, 30, 'products');


$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/categories.php';
$mainTabs->addTab( 'categories', $link,KS_TABS_CAT, 30 );

$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/orders.php';
$mainTabs->addTab( 'orders', $link,KS_TABS_ORDERS, 40 );

// subtab Orders
	$link = XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/admin/orders.php';
	$mainTabs->addSub( 'ordermain', $link,KS_SBTABORDR, 10, 'orders');

// subtab Order Status
	$link = XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/admin/order_status.php';
	$mainTabs->addSub( 'orderstat', $link,KS_SBTABSTAT, 20, 'orders');

// Text tab
$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/texts.php';
$mainTabs->addTab( 'text', $link,KS_TABS_TEXTS, 50 );

// subtab Text Main
	$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/texts.php?txt=1';
	$mainTabs->addSub( 'textmain', $link,KS_SUBTABS_TEXTS_MAIN, 10, 'text');

// subtab Terms
	$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/texts.php?txt=2';
	$mainTabs->addSub( 'textterms', $link,KS_SUBTABS_TEXTS_TERMS, 20, 'text');

// subtab Privacy
	$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/texts.php?txt=3';
	$mainTabs->addSub( 'textpriv', $link,KS_SUBTABS_TEXTS_PRIV, 30, 'text');
	
// subtab Returns
	$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/texts.php?txt=4';
	$mainTabs->addSub( 'textreturn', $link,KS_SUBTABS_TEXTS_RETURN, 40, 'text');
	
// subtab Company Info
	$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/texts.php?txt=5';
	$mainTabs->addSub( 'textinfo', $link,KS_SUBTABS_TEXTS_INFO, 50, 'text');
	

$perm_name = 'kshop_perm';
$perm_itemid = 1;
if ($xoopsUser) {
$groups = $xoopsUser->getGroups();
    } else {
        $groups = XOOPS_GROUP_ANONYMOUS;
    } 
$module_id = $xoopsModule->getVar('mid');
$gperm_handler =& xoops_gethandler('groupperm');

if ($gperm_handler->checkRight($perm_name, $perm_itemid, $groups, $module_id)) {
// Permissions tab
$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/permissions.php';
$mainTabs->addTab( 'permissions', $link,KS_TABS_PERMISSIONS, 60 );

// Plugins tab
$link = XOOPS_URL.'/modules/'.$xoopsModule->getVar('dirname').'/admin/plugins.php';
$mainTabs->addTab( 'plugins', $link,KS_TABS_PLUGINS, 70 );
}

	// subtab to have the option
	//$link = XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/admin/';
	//$mainTabs->addSub( 'indexsub', $link, 'Several', 10, 'index');


?>