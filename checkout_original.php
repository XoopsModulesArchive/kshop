<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+
include 'header.php';


(!isset($_POST['step'])) ? $sel='1' : $sel=$_POST['step'];
if (isset($_POST['step2back'])) $sel=1;
if (isset($_POST['step3back'])) $sel=2;

$sel=errorChecker($sel);

$kuser= new Kuser;

switch ($sel)
{
	case "1" :
	$xoopsOption['template_main'] = 'ks_checkout_step1.html';
	include XOOPS_ROOT_PATH.'/header.php';
	step1();
	break;

	case "2" :
	$xoopsOption['template_main'] = 'ks_checkout_step2.html';
	include XOOPS_ROOT_PATH.'/header.php';
	step2();
	break;

	case "3" :
	$xoopsOption['template_main'] = 'ks_checkout_step3.html';
	include XOOPS_ROOT_PATH.'/header.php';
	step3();
	break;

	case "4" :
	$xoopsOption['template_main'] = 'ks_checkout_step4.html';
	include XOOPS_ROOT_PATH.'/header.php';
	step4();
	break;

	case "5" :
	$xoopsOption['template_main'] = 'ks_checkout_step5.html';
	include XOOPS_ROOT_PATH.'/header.php';
	step5();
	break;

	default :
	$xoopsOption['template_main'] = 'ks_checkout_step1.html';
	include XOOPS_ROOT_PATH.'/header.php';
	step1();
	break;
}


/* ----------------------------------------------
STEP 1 This is where user inputs info
//------------------------------------------*/
function step1(){
	global $xoopsOption, $xoopsTpl, $xoopsModuleConfig, $kuser;
	//Header
	$xoopsTpl->assign('shopname', $xoopsModuleConfig['shopname']);
	$xoopsTpl->assign('xoops_pagetitle', 'Checkout');
	//Check which step in checkout process is current
	currChkoutStep(1);
	//Check for user data in DB.
	$userdata=$kuser->LoadUserData();
	$xoopsTpl->assign('userdata', $userdata);
	//Load countries
	$country=loadCountry();
	$xoopsTpl->assign('country', $country);
}


/* -------------------------------------------------------------
STEP 2 Check if user filled in all requiered fields. If so then show him payment
// -------------------------------------------------------------*/
function step2(){
	global $xoopsOption, $xoopsTpl, $xoopsModuleConfig, $kuser;
	//Check which step in checkout process is current for checkout header
	currChkoutStep(2);

	if ((!isset($_POST['step3back'])) || ($_POST['step']!=3) ){
		//Check for errors
		$error=$kuser->checkstep1Errors();
		if ($error){
			$xoopsOption['template_main'] = 'ks_checkout_step1.html';
			include_once XOOPS_ROOT_PATH.'/header.php';
			$xoopsTpl->assign('error', $error);
			step1();
			return;
		}
	}

	$plugs=new pluginManager;
	$payplugs=$plugs->plugTloader('payment'); //Load all installed payment plugin templates used in checkout.
	$xoopsTpl->assign('plugs', $payplugs);
}

/* -----------------------------------------------------
Shipping menu
// ------------------------------------------------------*/
function step3(){
	global $xoopsOption, $xoopsTpl, $xoopsModuleConfig, $kuser,$CART;

	if (!isset($_POST['payment'])){
	step2();
	return;
	}
	$CART->payment=$_POST['payment'];
	//Check which step in checkout process is current
	currChkoutStep(3);

	$plugs=new pluginManager;
	$shipplugs=$plugs->plugTloader('shipping'); //Load all installed payment plugin templates used in checkout.
	$xoopsTpl->assign('plugs', $shipplugs);
}


/* ------------------------------------
Final step. Confirmation form.
//----------------------------------*/
function step4 (){
	global $xoopsOption, $xoopsTpl, $xoopsModuleConfig, $kuser,$CART;
	$CART->shipping=$_POST['shipping'];
	//Check which step in checkout process is current
	currChkoutStep(4);
	cartTemplate();
	addedcartTemplate();
}


/* ------------------------------------
Process checkout. step 5
//----------------------------------*/

function step5(){
	global $kuser,$xoopsTpl,$xoopsModuleConfig,$xoopsConfig,$USER,$CART,$xoopsModule;
	$mail= new sendMail;
	$mail->sendXoopsMail();
	if (!$mail->ok){
	$xoopsTpl->assign('error', $mail->msg);
	$xoopsTpl->assign('orderok', 0);
	return;
	} else {
	$xoopsTpl->assign('orderok', 1);
	return;
	}


}



//For Kshop Header
$xoopsTpl->assign('carttotal', BuildPrice($CART->chkwhichPrice()));
$xoopsTpl->assign('prodstotal', $CART->countItems());
$xoopsTpl->assign('shopname', $xoopsModuleConfig['shopname']);
if (chckifCheckout()) $xoopsTpl->assign('allowcheck', 1);


//Prevent users from skipping to this page.
if (!chckifCheckout()){
	$CART->init();
	header("Location: index.php");
	exit;
}

$_SESSION['kshopcart'] = serialize($CART);
$_SESSION['kshopuser'] = serialize($USER);
include XOOPS_ROOT_PATH.'/footer.php';
?>