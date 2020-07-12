<?php

function process_emailinvoice ($price)
{
$pay=array();
$pay['added']=$price;
$pay['partial']=0;
$pay['text']='';
return $pay;
}


function mail_emailinvoice ($pay_loader){
	
	
	return $pay_loader;
}




function final_emailinvoice ($meu)
{

	//all good so return TRUE. If a problem had happened a message should be printed and FALSE should be returned
	
	return TRUE;
}

function chkout_emailinvoice(){
global $xoopsTpl;

$xoopsTpl->assign('mytest', 'bla bla');

}

?>