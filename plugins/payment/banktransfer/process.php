<?php

/* This calculates how bank transfer will affect the order total
The order total is passed from one group of plugins to another in the following order:
-Product Total. Adds all products
-Tax. tax is included in the product total so it doesn't afect it's value.
-Order total plugins. This will grab the Product total value and pass it through all installed order total plugins, changing it's value as it goes.
-Payment plugin. The order total is passed to the payment plugin
-Shipping total. Shipping total receives the changed total from payment and further processes it.
And thus we have a new total that's been processed by each plugin. The system is designed for diferent situations, such as only having payment plugins, or 1 order total and 1 shipping that affect the order total. However, THIS IS IMPORTANT, if you want 1 payment type that is free, it should still appear in the second step of the checkout process and clearly state that it's free. the module needs one payment and one shipping plugin installed to work.


//------------------------------------*/
//This is the "brains" part of the module that calculates how much is the cost of this module.
function process_banktransfer ($price){
global $xoopsModuleConfig, $xoopsDB;

//grab from DB the 2 values that will change the cart total. In this case one is a percentage and another a fixed value.
$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plug_banktransfer').' WHERE id = 1 ');
              $row = $xoopsDB->fetchArray($query);
              	$percentage = $row['percentage'];
              	$add_amount =$row['add_amount'];

//Check if percentage in NOT zero.
if (!empty($percentage)){
//calculate the new total using percentage and the fixed amount. In this case it's simple math.
$new_total = ($price * $percentage / 100)+$price+$add_amount;
//Calculate the partial value. In other words, the value that was added to the order total.
$partial=($price*$percentage/100)+$add_amount;
//Grab the text that will show on the confirm order (step 4) checkout.
$partialtext="$percentage% + ".$add_amount;
} else {
//If percentage IS empty then only use the fixed amount value to alter the order total value.
$new_total = $price + $add_amount;
$partial=$add_amount;
}

//Check if this plugin has a language file in the same language of xoops. The xoops language is defined in system/preferences.
$langfile=checkLang('payment','banktransfer');
include($langfile);

/*Now place all the new info in a array. This new array should have 3 components:
$pay['added']  This is the new total that will be passed to other plugins
$pay['partial'] This is the value that affected the order total before it went into this plugin
$pay['text']	The text that shows in step 4 of checkout
//------------------*/
$pay=array();
$pay['added']=$new_total;
$pay['partial']=$partial;
$pay['text']=PAYBNKTRSLINETXT.$partialtext;

return $pay;
}
	

//-------------------------*/
//---- Final Step for checkout. this final step allows each plugin to perform an action upon completion of the order. In the case of bank transfer it will send an extra email. However you can tailor this to your own needs. Final step is processed after the order has been inserted into the DB and the order email has been sent.

//Send extra email to client with your bank details. $oid is the order id.
function final_banktransfer ($oid)
{
global $xoopsModuleConfig,$xoopsConfig,$USER,$CART,$xoopsTpl,$xoopsDB,$xoopsModule;

//Load clients email.
$email=$USER['email'];
		
//Load bank details from DB. These will be sent in the email.
$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plug_banktransfer').' WHERE id = 1 ');
              $row = $xoopsDB->fetchArray($query);
              	$accholder = $row['acc_holder'];
              	$bankname =$row['bank_name'];
              	$bankbranch =$row['bank_branch'];
              	$accnum =$row['acc_num'];
              	$intnum =$row['inter_num'];


$xoopsMailer =getMailer(); //Get mailer object
$xoopsMailer->useMail(); // Set it to use email (as opposed to PM)

//Check if mail template exists in xoops language. If not use default english.
$template= XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/payment/banktransfer/language/'.$xoopsConfig['language'].'_bank_details.tpl';
$tempfile=$xoopsConfig['language'].'_bank_details.tpl';
if (!file_exists($template)){
$template= XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/payment/banktransfer/language/english_bank_details.tpl';
$tempfile='english_bank_details.tpl';
}

//Assign info to template
$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/payment/banktransfer/language/');
$xoopsMailer->setTemplate($tempfile);
$xoopsMailer->assign('ACCTHOLDER', $accholder);
$xoopsMailer->assign('ORDERNUM', $oid);
$xoopsMailer->assign('BANKNAME', $bankname);
$xoopsMailer->assign('BRANCH', $bankbranch);
$xoopsMailer->assign('ACCTNUM', $accnum);
$xoopsMailer->assign('INTNUM', $intnum);
$xoopsMailer->assign('SHOP', $xoopsModuleConfig['shopname']);

$xoopsMailer->setToEmails( array($email, $xoopsModuleConfig['mailorder']) );
$xoopsMailer->setFromEmail($xoopsModuleConfig['mailcontact']);
$xoopsMailer->setFromName($xoopsModuleConfig['shopname']);

$xoopsMailer->setSubject(PAYBNKTRSDL1);

//If email could NOT be sent, it will return an eror message and what went wrong using $mailer->getErrors()
if (!$xoopsMailer->send()) {
$error= 'unable to send bank transfer mail <br />'.$xoopsMailer->getErrors();
return $error;
}

	return TRUE;
}

function chkout_banktransfer(){
global $xoopsTpl;

$xoopsTpl->assign('mytest', 'bla bla');

}

?>