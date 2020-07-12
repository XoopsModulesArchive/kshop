<?php

function random_products($options){
global $xoopsDB;

if ($options[3]==1){
$query = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("kshop_products")." WHERE p_has_special=1 ORDER BY RAND() LIMIT 0, $options[1]");
} else {
$query = $xoopsDB->query("SELECT * FROM ".$xoopsDB->prefix("kshop_products")." ORDER BY RAND() LIMIT 0, $options[1]");
}

$prods = array();
$q=1;
while ($row = $xoopsDB->fetchArray($query))
{
$prods[$q]['id']=$row['p_id'];
$prods[$q]['name']=$row['p_name'];
$prods[$q]['desc']=$row['p_desc'];
$prods[$q]['img']=$row['p_thumb'];
$prods[$q]['price']=$row['p_price'];
$prods[$q]['sprice']=$row['p_sp_price'];
if (($row['p_sp_price']!='0') AND ($row['p_has_special']==1)) $prods[$q]['hasspec']=1;
$q++;
}
$block['products']=$prods;
$block['showdesc']=$options[0];
$block['onlyspec']=$options[3];
$block['numcol']=$options[2];

return $block;
}

function random_products_edit($options){
require_once XOOPS_ROOT_PATH . '/modules/kshop/language/english/modinfo.php';

	
	$form = _KS_BLKSWSHDESC."<input type='text' size='1' name='options[0]' value='$options[0]' />";
	$form .= "<br />";
	$form .= _KS_BLKNUMPROD."<input type='text' size='1' name='options[1]' value='$options[1]' />";
	$form .= "<br />";
	$form .= _KS_BLKNUMCOL."<input type='text' size='1' name='options[2]' value='$options[2]' />";
	$form .= "<br />";
	$form .= _KS_BLKONLYSPEC."<input type='text' size='1' name='options[3]' value='$options[3]' />";
	return $form;
}


?>