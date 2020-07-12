<?php

include '../../plugin_header.php';
	
//load language file
$langfile=checkLang('order','discountabsolut');
include($langfile);

	
$msg="";
//Check if submit button was pressed, if yes execute what's inside.
if (isset($_POST['submit']))
{

//Update table with text boxes. Since there is only one row, id=1 to constantly update the same row.
$query = "UPDATE ".$xoopsDB->prefix("kshop_plug_discountabsolut")." SET order_total = '{$_REQUEST['totalbox']}', discount = '{$_REQUEST['discountbox']}'  WHERE id = 1";
$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error";
		}
				$msg="Settings Updated Sucessfully!";
}

//load settings from DB.
$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plug_discountabsolut'));
              $row = $xoopsDB->fetchArray($query);
              	$order_total = $row['order_total'];
              	$discount =$row['discount'];
				
			$xoopsTpl->assign('order_total', $row['order_total']);
			$xoopsTpl->assign('discount', $row['discount']);
			$xoopsTpl->assign('msg', $msg);
			
$xoopsTpl->display(XOOPS_ROOT_PATH.'/modules/kshop/plugins/order/discountabsolut/templates/discountabsolut.html');

?>