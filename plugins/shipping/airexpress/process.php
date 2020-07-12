<?php

function process_airexpress ($price)
{
global $xoopsModuleConfig, $xoopsDB;

	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plug_airexpress'));
              $row = $xoopsDB->fetchArray($query);
              	$cost = $row['cost'];
				
				$total=$price+$cost;
	
$langfile=checkLang('shipping','airexpress');
include($langfile);
			
$ship=array();
$ship['added']=$total;
$ship['partial']=$cost;
$ship['text']=SHIPAIREXPRES;
return $ship;
}



function final_airexpress ()
{
	//all good so return TRUE. If a problem had happened a message should be printed and FALSE should be returned
	
	return TRUE;
}


function chkout_airexpress(){
global $xoopsTpl;

$xoopsTpl->assign('mytest', 'bla bla');

}

?>