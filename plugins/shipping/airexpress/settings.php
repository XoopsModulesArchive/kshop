<?php
include '../../plugin_header.php';

//load language file
$langfile=checkLang('shipping','airexpress');
include($langfile);


$msg="";
//Check if submit button was pressed, if yes execute what's inside.
if (isset($_POST['submit']))
{

$query = "UPDATE ".$xoopsDB->prefix("kshop_plug_airexpress")." 
SET cost = '{$_POST['cost']}' 
WHERE id = 1";
$res=$xoopsDB->queryF($query);
		if(!$res) {
			echo "error: $query";
		}
		$msg="Settings Updated Sucessfully!";
	}
	
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plug_airexpress'));
              $row = $xoopsDB->fetchArray($query);
              	
$xoopsTpl->assign('msg', $msg);
$xoopsTpl->assign('cost', $row['cost']);
	
$xoopsTpl->display(XOOPS_ROOT_PATH.'/modules/kshop/plugins/shipping/airexpress/templates/airexpress.html');
	
?>