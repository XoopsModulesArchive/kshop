<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+
include 'header.php';

$kuser= new Kuser;

if (!isset($_POST['step'])) 
{
$sel=1;
} else {
$sel=$_POST['step'];
$newsel=errorChecker();
}



//if (isset($newsel['step'])) $sel=$newsel['step'];
//$sel=$newsel['step'];
if (isset($_POST['step2back'])) $sel=1;
if (isset($_POST['step3back'])) $sel=2;



switch ($sel)
{
	//User Info
	case "1" :
	$xoopsOption['template_main'] = 'ks_checkout_step1.html';
	include XOOPS_ROOT_PATH.'/header.php';
	if (isset($newsel['error'])){
	$xoopsTpl->assign('error', $newsel['error']);
	}
	step1();
	break;

	//Payment Method
	case "2" :
	$xoopsOption['template_main'] = 'ks_checkout_step2.html';
	include XOOPS_ROOT_PATH.'/header.php';
	if (isset($newsel['error'])){
	$xoopsTpl->assign('kserror', $newsel['error']);
	}
	step2();
	break;

	//Shipping method
	case "3" :
	$xoopsOption['template_main'] = 'ks_checkout_step3.html';
	include XOOPS_ROOT_PATH.'/header.php';
	if (isset($newsel['error'])){
	$xoopsTpl->assign('kserror', $newsel['error']);
	}
	step3();
	break;

	//Final confirmation
	case "4" :
	$xoopsOption['template_main'] = 'ks_checkout_step4.html';
	include XOOPS_ROOT_PATH.'/header.php';
	step4();
	break;

	//Process order
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

//Checks for errors in each step.
function errorChecker(){
global $xoopsOption, $xoopsTpl, $xoopsModuleConfig, $kuser, $sel;
$newsel=array();

//Step 1 error check. Check if all requiered fields were filled.
	if ($sel==2) {
		//Check for errors
		$error=$kuser->checkstep1Errors();
		if ($error){
		$sel=1;
		$newsel['error']=$error;
		return $newsel;
		} else {
		$sel=2;
		return $newsel;
		}
		}
		
	//Step 2 check if a payment method was selected.
	if ($sel==3) {
	if (!isset($_POST['payment'])){
	$sel=2;
	$newsel['error']=KS_MISSEL;
	return $newsel;
	} else {
	$sel=3;
	return $newsel;
	}
	}
	
	//Step 3 check if a shipping method was selected.
	if ($sel==4) {
	if (!isset($_POST['shipping'])){
	$sel=3;
	$newsel['error']=KS_SHIPMAND;
	return $newsel;
	} else {
	$sel=4;
	return $newsel;
	}
	}

return;

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
STEP 2 Payment menu
// -------------------------------------------------------------*/
function step2(){
	global $xoopsOption, $xoopsTpl, $xoopsModuleConfig, $kuser;
	//Check which step in checkout process is current for checkout header
	currChkoutStep(2);

	$plugs=new pluginManager;
	$payplugs=$plugs->plugTloader('payment'); //Load all installed payment plugin templates used in checkout.
	$xoopsTpl->assign('plugs', $payplugs);
}

/* -----------------------------------------------------
STEP 3 Shipping menu
// ------------------------------------------------------*/
function step3(){
	global $xoopsOption, $xoopsTpl, $xoopsModuleConfig, $kuser,$CART;
	
	if (isset($_POST['payment'])) $CART->payment=$_POST['payment']; //check if a payment was selected. If it was load that into the session.

	//Check which step in checkout process is current
	currChkoutStep(3);

	$plugs=new pluginManager;
	$shipplugs=$plugs->plugTloader('shipping'); //Load all installed shipment plugin templates used in checkout.
	$xoopsTpl->assign('plugs', $shipplugs);
}


/* ------------------------------------
Final step. Confirmation form.
//----------------------------------*/
function step4 (){
	global $xoopsOption, $xoopsTpl, $xoopsModuleConfig, $kuser,$CART;

	if (isset($_POST['shipping'])) $CART->shipping=$_POST['shipping']; //check if a shipping was selected. If it was load that into the session.

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