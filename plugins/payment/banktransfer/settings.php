<?php

include '../../plugin_header.php';


//load language file
$langfile=checkLang('payment','banktransfer');
include($langfile);

$msg="";

//Check if submit button was pressed, if yes execute what's inside.
if (isset($_POST['submit']))
{

//Update table with text boxes. Since there is only one row, id=1 to constantly update the same row.
$query = "UPDATE ".$xoopsDB->prefix("kshop_plug_banktransfer")." 
SET percentage = '{$_POST['percentage']}', 
add_amount = '{$_POST['add_amount']}', 
acc_holder = '{$_POST['acc_holder']}', 
bank_name = '{$_POST['bank_name']}', 
bank_branch = '{$_POST['bank_branch']}', 
acc_num = '{$_POST['acc_num']}', 
inter_num = '{$_POST['inter_num']}' 
WHERE id = 1";
$res=$xoopsDB->queryF($query);
		if(!$res) {
			echo "error: <br>";
			
		}	
		$msg="Settings Updated Sucessfully!";
}

//load settings from DB.
$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plug_banktransfer'));
              $row = $xoopsDB->fetchArray($query);
			  				
$xoopsTpl->assign('percentage', $row['percentage']);
$xoopsTpl->assign('add_amount', $row['add_amount']);
$xoopsTpl->assign('acc_holder', $row['acc_holder']);
$xoopsTpl->assign('bank_name', $row['bank_name']);
$xoopsTpl->assign('bank_branch', $row['bank_branch']);
$xoopsTpl->assign('acc_num', $row['acc_num']);
$xoopsTpl->assign('inter_num', $row['inter_num']);

$xoopsTpl->display(XOOPS_ROOT_PATH.'/modules/kshop/plugins/payment/banktransfer/templates/bank_settings.html');
?>