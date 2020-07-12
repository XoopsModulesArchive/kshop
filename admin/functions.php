<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

function checkPerm($perm){
global $xoopsUser,$xoopsModule;

$perm_name = 'kshop_perm';
$perm_itemid = $perm;
if ($xoopsUser) {
$groups = $xoopsUser->getGroups();
    } else {
        $groups = XOOPS_GROUP_ANONYMOUS;
    } 
$module_id = $xoopsModule->getVar('mid');
$gperm_handler =& xoops_gethandler('groupperm');

if ($gperm_handler->checkRight($perm_name, $perm_itemid, $groups, $module_id)) {

} else{
redirect_header('products.php', 1, KS_PERNONE);
die();
}

}
?>