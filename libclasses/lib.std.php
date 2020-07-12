<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+



function chckifCheckout(){
	global $xoopsModuleConfig, $CART, $xoopsUser;

	if (($xoopsModuleConfig['reqlogin']==1) && (!is_object($xoopsUser))) return FALSE;
	if (($xoopsModuleConfig['minorder']!=0) && ($xoopsModuleConfig['minorder']>$CART->chkwhichPrice())) return FALSE;
	return TRUE;
}



//This function adds functionality to cartTemplate() which is needed for step 4 of the checkout procedure.
function addedcartTemplate(){
	global $CART,$xoopsTpl,$xoopsModuleConfig;

	$payment=$CART->loadPayment($CART->totalplugs);
	$shipping=$CART->loadShipping($payment['added']);
	$xoopsTpl->assign('finaltotal', BuildPrice($shipping['added']));
	$CART->totalfinal=$shipping['added'];
	$shipping['added']=BuildPrice($shipping['added']);
	$shipping['partial']=BuildPrice($shipping['partial']);
	$xoopsTpl->assign('shipping', $shipping);
	$payment['added']=BuildPrice($payment['added']);
	$payment['partial']=BuildPrice($payment['partial']);
	$xoopsTpl->assign('payment', $payment);

}


function cartTemplate(){
	global $CART,$xoopsTpl,$xoopsModuleConfig;

	$prods=array();
	$q=1;
	$carttotal=0;
	foreach ($CART->items as $key => $value){
		$ldprod=loadxProd($key);
		$carttotal+=rightPrice($ldprod)*$value;
		$prods[$q]=$ldprod;
		$prods[$q]['totalprice']=BuildPrice(rightPrice($ldprod)*$value);
		$prods[$q]['itemprice']=BuildPrice(rightPrice($ldprod));
		$prods[$q]['quantity']=$value;
		$q++;
	}

	//Build Tax part
	$xoopsTpl->assign('carttax', $xoopsModuleConfig['globaltax']);
	$xoopsTpl->assign('carttaxpartial', BuildPrice(getTax($CART->productTotal())));
	$xoopsTpl->assign('ctotal', BuildPrice($CART->total));

	//Check if there is any products in cart.
	if (!empty($prods)){
		$xoopsTpl->assign('prods', $prods);
		$xoopsTpl->assign('empty', 0);
	} else {
		$xoopsTpl->assign('empty', 1);
	}


	$otplugs=$CART->ortotLoader($CART->total);

	if (!$otplugs){
		$xoopsTpl->assign('hasplugs', 0);
		$xoopsTpl->assign('finaltotal', BuildPrice($CART->total));
	} else {
		$xoopsTpl->assign('otplugs', $otplugs);
		$xoopsTpl->assign('hasplugs', 1);
		$xoopsTpl->assign('finaltotal', BuildPrice($CART->totalplugs));
	}

}




//Check which step in checkout process is current
function currChkoutStep($step){
	global $xoopsTpl;

	$xoopsTpl->assign('chkstep1', 'chout1');
	$xoopsTpl->assign('chkstep2', 'chout1');
	$xoopsTpl->assign('chkstep3', 'chout1');
	$xoopsTpl->assign('chkstep4', 'chout1');

	if ($step==1) $xoopsTpl->assign('chkstep1', 'chout2');
	if ($step==2) $xoopsTpl->assign('chkstep2', 'chout2');
	if ($step==3) $xoopsTpl->assign('chkstep3', 'chout2');
	if ($step==4) $xoopsTpl->assign('chkstep4', 'chout2');
}

function getTax($total)
{
	global $xoopsModuleConfig,$CART;
	$taxtot=$total*$xoopsModuleConfig['globaltax']/100;

	return $taxtot;
}

//Check which price to use. Either spcial price or regular price.
function rightPrice($prod){
	if (($prod['has_special']==1) && (!empty($prod['sp_price'])))
	{
		$price=$prod['sp_price'];
	} else {
		$price=$prod['price'];
	}
	return $price;
}


function FormatNumber($str,$decimal_padding="0"){
	global $xoopsModuleConfig;

	$decimal_places=$xoopsModuleConfig['decimal'];
	if ($decimal_places==0){
		$str =number_format($str, 0);
		return $str;
	}

	// firstly format number and shorten any extra decimal places
	// Note this will round off the number pre-format $str if you dont want this fucntionality
	$str          =  number_format($str,$decimal_places,'.','');    // will return 12345.67
	$number      = explode('.',$str);
	$number[1]    = (isset($number[1]))?$number[1]:''; // to fix the PHP Notice error if str does not contain a decimal placing.
	$decimal    = str_pad($number[1],$decimal_places,$decimal_padding);
	return (float) $number[0].'.'.$decimal;
}

function BuildPrice($price){
	global $xoopsModuleConfig;

	$price=FormatNumber($price);

	if ($xoopsModuleConfig['currencyfirst']==1){
		$new_price=$xoopsModuleConfig['currency']." ".$price;
	}else{
		$new_price=$price." ".$xoopsModuleConfig['currency'];
	}
	return $new_price;
}

/**
* Get data of a GIF-, JPG-, PNG- or SWF-file.
* getimagesize() returns an array of 4 elements.
* We use the 3rd index which returns a string in the format of
* "height=xxx width=xxx" for use in the HTML IMG-tag.
*/
function getImageInfo($image)
{
	$size = getimagesize($image);

	return $size[3];
}

?>