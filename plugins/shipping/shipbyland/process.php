<?php

function process_shipbyland ($price)
{

$langfile=checkLang('shipping','shipbyland');
include($langfile);

$ship=array();
$ship['added']=$price;
$ship['partial']="";
$ship['text']=SHIPLAND;
return $ship;
}






function final_shipbyland ()
{

	//all good so return TRUE. If a problem had happened a message should be printed and FALSE should be returned
	
	return TRUE;
}

function chkout_shipbyland(){
global $xoopsTpl;

$xoopsTpl->assign('mytest', 'bla bla');

}

?>