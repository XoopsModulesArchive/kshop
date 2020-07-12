<?php
include '../../plugin_header.php';

//load language file
$langfile=checkLang('payment','paydeliver');
include($langfile);

$msg='';
//Check if submit button was pressed, if yes execute what's inside.
if (isset($_POST['submit']))
{

//Update table with text boxes. Since there is only one row, id=1 to constantly update the same row.
$query = "UPDATE ".$xoopsDB->prefix("kshop_plug_paydeliver")." SET cost = '{$_REQUEST['cost']}'  WHERE id = 1";
$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error";
		}
		$msg="Settings Updated Sucessfully!";
}

//load settings from DB.
$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plug_paydeliver'));
              $row = $xoopsDB->fetchArray($query);

$xoopsTpl->assign('msg', $msg);
$xoopsTpl->assign('cost', $row['cost']);

              	
$xoopsTpl->display(XOOPS_ROOT_PATH.'/modules/kshop/plugins/payment/paydeliver/templates/settings.html');

?>