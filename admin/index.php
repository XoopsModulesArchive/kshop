<?php

// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

include 'admin_header.php';

xoops_cp_header();


$mainTabs->setCurrent('index', 'tabs');
$mainTabs->display();



$xoopsTpl->display('db:admin_main.html');
xoops_cp_footer();
?>