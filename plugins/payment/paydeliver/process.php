<?php

function process_paydeliver ($price)
{
global $xoopsModuleConfig, $xoopsDB;

$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plug_paydeliver').' WHERE id = 1 ');
              $row = $xoopsDB->fetchArray($query);
              	$cost = $row['cost'];
              	
				
$new_total=$cost+$price;

$langfile=checkLang('payment','paydeliver');
include($langfile);

$pay=array();
$pay['added']=$new_total;
$pay['partial']=$cost;
$pay['text']=PAYDELSET3;
return $pay;
}


function mail_paydeliver (&$pay_loader){
	
	$total_price=$pay_loader["prod_total"];
	$plug_val=process_paydeliver ($total_price);
	
	$new_total=$total_price+$plug_val;
	
	$line="<tr><td></td><td></td><td>Payment on Delivery:</td><td>$plug_val</td><td>$new_total</td></tr>";
	
	$pay_loader["prod_total"]=$new_total;
	$pay_loader["line"].=$line;
	return $pay_loader;
}




function final_paydeliver ()
{

	//all good so return TRUE. If a problem had happened a message should be printed and FALSE should be returned
	
	return TRUE;
}


function chkout_paydeliver(){
global $xoopsTpl;

$xoopsTpl->assign('mytest', 'bla bla');

}

?>