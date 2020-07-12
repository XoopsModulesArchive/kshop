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
$insoption=insertOption($_POST['newfield']);
}

// Delete option from DB
if (isset($_POST['del']) && isset($_POST) && !empty($_POST['options']))
{
$options=delOption($_POST['options']);
}

$options=buildOptions();
$xoopsTpl->assign('options', $options);

$xoopsTpl->display('db:admin_options.html');

xoops_cp_footer();
?>