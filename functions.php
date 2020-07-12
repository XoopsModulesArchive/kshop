<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

function srchCat($sid){
	global $xoopsDB,$xoopsModuleConfig;

	$q=1;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_categories') . " WHERE c_name LIKE '%$sid%' OR c_description LIKE '%$sid%'");
	while ($row = $xoopsDB->fetchArray($query))
	{
		$srch[$q]['id']=$row['c_id'];
		$srch[$q]['name']=$row['c_name'];
		$q++;
	}
	return $srch;
}

function srchProd($sid){
	global $xoopsDB,$xoopsModuleConfig;

	$q=1;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products') . " WHERE p_desc LIKE '%$sid%' OR p_desc_long LIKE '%$sid%' OR p_name LIKE '%$sid%'");
	while ($row = $xoopsDB->fetchArray($query))
	{
		$srch[$q]['id']=$row['p_id'];
		$srch[$q]['name']=$row['p_name'];
		$q++;
	}
	return $srch;
}


?>