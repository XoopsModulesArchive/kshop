<?php

// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

class sendMail extends Order
{

	function sendXoopsMail(){
		global $xoopsModuleConfig,$xoopsConfig,$USER,$CART,$prod, $xoopsTpl,$plugortot;

		$lastid=$this->saveOrder();
		$this->buildMail();
		$lastcheck=$this->finalStepCheck($lastid);
	}


	function buildMail(){
		global $xoopsModuleConfig,$xoopsConfig,$USER,$CART,$xoopsTpl;

		$payment=$CART->payment;
		$shipping=$CART->shipping;
		//Load all session fields into vars.
		$firstname=$USER['firstname'];
		$lastname=$USER['lastname'];
		$company=$USER['company'];
		$address=$USER['address'];
		$zipcode=$USER['zipcode'];
		$city=$USER['city'];
		$country=$USER['country'];
		$tel=$USER['tel'];
		$fax=$USER['fax'];
		$email=$USER['email'];
		$comment=$USER['comment'];


		cartTemplate();
		addedcartTemplate();

		$subject = $xoopsModuleConfig['subjectline'];
		$timeoforder  = date("d.m.Y") ." - ". date("H:i");
		$mailer = getMailer();
		$mailer->useMail();

		//$xoopsTpl->assign('prods', $orderoutput);
		$xoopsTpl->assign('sitename', $xoopsConfig['sitename']);
		$xoopsTpl->assign('storename', $xoopsModuleConfig['shopname']);
		$xoopsTpl->assign('adminmail', $xoopsConfig['adminmail']);
		$xoopsTpl->assign('firstname', $firstname);
		$xoopsTpl->assign('lastname', $lastname);
		$xoopsTpl->assign('company', $company);
		$xoopsTpl->assign('street', $address);
		$xoopsTpl->assign('city', $city);
		$xoopsTpl->assign('zip', $zipcode);
		$xoopsTpl->assign('country', $country);
		$xoopsTpl->assign('telefone', $tel);
		$xoopsTpl->assign('fax', $fax);
		$xoopsTpl->assign('email', $email);
		$xoopsTpl->assign('comment', $comment);
		$xoopsTpl->assign('timeoforder', $timeoforder);
		$xoopsTpl->assign('clientip', $_SERVER['REMOTE_ADDR']);
		$xoopsTpl->assign('clientrmtadrs', gethostbyaddr($_SERVER['REMOTE_ADDR']));
		$xoopsTpl->assign('paymethod', $payment);
		$xoopsTpl->assign('shipmethod', $shipping);

		$content = $xoopsTpl->fetch("db:ks_send_mail.html");

		$mailer->setBody($content);
		$mailer->setToEmails( array($email, $xoopsModuleConfig['mailorder']) );
		$mailer->setFromEmail($xoopsModuleConfig['mailcontact']);
		$mailer->setFromName($xoopsModuleConfig['shopname']);
		$mailer->setSubject($subject);
		$mailer->multimailer->isHTML(true);

		if ($mailer->send()) {
			//If debug is turned on, list every every step
			if ($xoopsModuleConfig['debug']) $this->msg.="Send Mail OK <br />";		

		} else {
			$this->ok=FALSE;
			$this->msg.="error in buildMail():".$mailer->getErrors()."<br />";
		}

	}


	//perform a final step for each plugin, if all is ok, continue process.
	function finalStepCheck($oid){
		global $xoopsDB,$CART,$xoopsModule,$xoopsConfig;


		//Order Total plugins
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_plugins') . " WHERE type='order' ");
		while ($row = $xoopsDB->fetchArray($query))
		{
			$order_id =$row['id'];
			$order_dir = $row['dir'];
			$langfile=checkLang('order',$order_dir);
			include_once($langfile);
			include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/order/'.$order_dir.'/process.php';
			$processfinal="final_".$order_dir;
			$info=$processfinal($oid);
		}
		//---------------------*/

		//Payment plugin
		$pay=$CART->payment;
		$langfile=checkLang('payment',$pay);
		include_once($langfile);
		include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/payment/'.$pay.'/process.php';
		$processfinal="final_".$pay;
		$info=$processfinal($oid);
		//---------------------------*/

		//Shipping Plugin
		$ship=$CART->shipping;
		$langfile=checkLang('shipping',$ship);
		include_once($langfile);
		include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/plugins/shipping/'.$ship.'/process.php';
		$processfinal="final_".$ship;
		$info=$processfinal($oid);
		//---------------------*/
	}

}

?>