<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

include 'admin_header.php';

xoops_cp_header();

$mainTabs->setCurrent('products', 'tabs');
$mainTabs->display();


// Insert new option into DB
if (isset($_POST['addnew']) && isset($_POST) && !empty($_POST['newfield']))
{
$brands=insertBrand($_POST['newfield']);
}

// Delete option from DB
if (isset($_POST['del']) && isset($_POST) && !empty($_POST['brands']))
{
$brands=delBrand($_POST['brands']);
}

$brands=buildBrands();
$xoopsTpl->assign('brands', $brands);

$xoopsTpl->display('db:admin_brands.html');

xoops_cp_footer();
?>