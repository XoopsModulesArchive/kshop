<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

include 'admin_header.php';

xoops_cp_header();

$mainTabs->setCurrent('orders', 'tabs');
$mainTabs->display();

/*---------------------------------------------*/
// Change Default order status
if (isset($_POST['setDefault']) && isset($_POST) && !empty($_POST['setDefault']))
{
	//set all options to 0
	$query = "UPDATE ".$xoopsDB->prefix("kshop_status")." SET deff=0 ";
	$res=$xoopsDB->query($query);
	if(!$res) echo "error: $query";
	
	//now set the correct option to default, by setting deff=1
	$newdef=$_POST['options'];
	$query = "UPDATE ".$xoopsDB->prefix("kshop_status")." SET deff=1 WHERE sid='$newdef' ";
	$res=$xoopsDB->query($query);
	if(!$res) echo "error: $query";
}

/*---------------------------------------------*/
// Insert new option into DB
if (isset($_POST['addnew']) && isset($_POST) && !empty($_POST['newfield']))
{
	$myts = myTextSanitizer::getInstance();
	$text_q = $myts->addslashes($_POST['newfield']);
	$query = "Insert into ".$xoopsDB->prefix("kshop_status")." (status) values ('$text_q')";
	$res=$xoopsDB->query($query);
	if(!$res) {
		echo "error: $query";
	}
}

/*---------------------------------------------*/
// Delete option from DB
if (isset($_POST['del']) && isset($_POST) && !empty($_POST['options']))
{
	$count=count($_POST['options']);
	for ($i=0; $i<$count; $i++) {
		//Delete from options table.
		$query = "Delete from ".$xoopsDB->prefix("kshop_status")." where sid='".$_POST['options'][$i]."'";
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error: $query";
		}
	}
}

$q=1;
$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_status'));
while ($row = $xoopsDB->fetchArray($query))
{
$stat[$q]['id']=$row['sid'];
$stat[$q]['name']=$row['status'];
if ($row['deff']==1){
$stat[$q]['sel']='selected';
$curr=$stat[$q]['name'];
}
$q++;
}

$xoopsTpl->assign('stats', $stat);
$xoopsTpl->assign('curr', $curr);


$xoopsTpl->display('db:admin_order_status.html');

xoops_cp_footer();
?>