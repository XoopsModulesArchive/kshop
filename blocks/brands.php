<?php

function ks_brands(){
global $xoopsDB;
$dirname='kshop';
include_once XOOPS_ROOT_PATH . '/modules/'.$dirname.'/libclasses/lib.shop.php';

$brands=buildBrands();

$block['brands']=$brands;
$block['kshop_dirname']=$dirname;



return $block;
}



?>