<?php

function process_discountabsolut($total){
	global $xoopsModuleConfig, $xoopsDB;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plug_discountabsolut') . " WHERE id='1' ");
	$row = $xoopsDB->fetchArray($query);
	$dis_total =$row['order_total'];
	$discount =$row['discount'];

	
	if ($total>=$dis_total)
	{
		$new_total=$total * $discount / 100;
		$new_total=number_format($new_total, 2, '.', '');
		$p_with_dis=$new_total;
		$total=$total-$p_with_dis;
	}
	
	if (isset($p_with_dis))
	{
$langfile=checkLang('order','discountabsolut');
include_once($langfile);
$pot['partial']=$p_with_dis;
$pot['added']=$total;
$pot['text']=$discount.DISABSOLLINE;
	return $pot;
	} else {
	return false;
	}
}



function final_discountabsolut ()
{

	//all good so return TRUE. If a problem had happened a message should be printed and FALSE should be returned

	return TRUE;
}

?>