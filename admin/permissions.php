<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

include 'admin_header.php';
xoops_cp_header();

//Check if user is allowed to view this page
checkPerm(1);


include_once XOOPS_ROOT_PATH.'/class/xoopsform/grouppermform.php';
$module_id = $xoopsModule->getVar('mid');
	
$mainTabs->setCurrent('permissions', 'tabs');
$mainTabs->display();

$item_list = array('1' => KS_PER1);
$title_of_form = KS_PERTITLE;
$perm_name = 'kshop_perm';
$perm_desc = KS_PERDESC;

    $form = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc);
    foreach ($item_list as $item_id => $item_name) {
    $form->addItem($item_id, $item_name);
    }
    echo $form->render(); 

//$xoopsTpl->assign('foo', $bar);



//$xoopsTpl->display('db:admin_textarea.html');
xoops_cp_footer();
?>