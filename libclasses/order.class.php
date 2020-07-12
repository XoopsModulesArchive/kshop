<?php

// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

class Order
{

	var $oid=NULL; //order id
	var $ok=TRUE; //check if all steps are ok
	var $msg=''; //holds all error messages



	//------------------------
	// Save order
	//--------------------------
	function saveOrder()
	{
		global $xoopsDB,$xoopsUser,$CART,$kuser,$xoopsModuleConfig;


		(is_object($xoopsUser)) ? $xid = $xoopsUser->uid() : $xid ='';

		$myts = myTextSanitizer::getInstance();
		$total=$myts->addslashes(FormatNumber($CART->totalfinal));
		$payment=$myts->addslashes($CART->payment);
		$shipping=$myts->addslashes($CART->shipping);
		$xid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : KS_ANONYMS;
		$comment='';

		$query = "Insert into ".$xoopsDB->prefix("kshop_orders").
		" (xuser,comment,paymethod,shipmethod,prodtotal)
	values 
	('$xid','$comment','$payment','$shipping','$total' )";

		$res=$xoopsDB->query($query);
		if(!$res) {
			$this->ok=FALSE;
			$this->msg.="error in saveOrder(): $query <br />";
			return FALSE;
		}
		//Get id of current order
		$orderid=mysql_insert_id();
		
		//save products for this order
		$this->saveOrderProd($orderid);
		
		//save status for this order
		$this->saveorderStatus($orderid);
		
		//save user for this order
		$kuser->saveUserOrder($orderid);
		//If debug is turned on, list every every step
		if ($xoopsModuleConfig['debug']) $this->msg.="Order User OK <br />";		

		return $orderid;
	}
	

	function saveOrderProd($lastid){
		global $xoopsDB,$xoopsUser,$CART,$xoopsModuleConfig;
		$q=1;
		foreach ($CART->items as $key=>$value){
			$prod=loadxProd($key);
			$p_num=$prod["item_nr"];
			$p_name=$prod["name"];
			$price=rightPrice($prod);

			$query = "Insert into ".$xoopsDB->prefix("kshop_orders_products")." (order_id,qty,p_num,p_name,p_price) values ('$lastid','$value','$p_num','$p_name','$price')";
			$res=$xoopsDB->query($query);
			if(!$res) {
				$this->ok=FALSE;
				$this->msg.="error in saveOrderProd(): $query <br />";
				return FALSE;
			}
			//If debug is turned on, list every step
			if ($xoopsModuleConfig['debug']) $this->msg.="Order Products OK <br />";		
		}
	}


	function saveorderStatus($oid,$status,$comment=''){
		global $xoopsDB,$xoopsModuleConfig;

		//Insert new order status into DB
		$query = "Insert into ".$xoopsDB->prefix("kshop_orders_status")." (orderid, status, comment) values ('$oid','$status','$comment')";
		$res=$xoopsDB->queryF($query);
		if(!$res) {
			$this->ok=FALSE;
			$this->msg.="error in saveorderStatus(): $query <br />";
			return FALSE;
			} 
			//If debug is turned on, list every every step
			if ($xoopsModuleConfig['debug']) $this->msg.="Order Status OK <br />";		
	}



	//----------------------------------
	// Erase one or several orders
	//-----------------------------------*/
	function eraseOrder($allorders)
	{
		global $xoopsDB;
		foreach ($allorders as $myval){

			//erase all products in order
			$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_orders_products')." WHERE order_id='$myval' ");
			$pnum = $xoopsDB->getRowsNum($query);
			for ($i=1; $i<=$pnum; $i++){
				$query = "Delete from ".$xoopsDB->prefix("kshop_orders_products")." WHERE order_id='$myval' ";
				$res=$xoopsDB->query($query);
				if(!$res) {
					$this->ok=FALSE;
					$msg="error: $query";
					return false;
				}
			}

			//erase all status order
			$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_orders_status')." WHERE orderid='$myval' ");
			$pnum = $xoopsDB->getRowsNum($query);
			for ($i=1; $i<=$pnum; $i++){
				$query = "Delete from ".$xoopsDB->prefix("kshop_orders_status")." WHERE orderid='$myval' ";
				$res=$xoopsDB->query($query);
				if(!$res) {
					echo "error: $query";
					return false;
				}
			}

			//erase user info for order
			$query = "Delete from ".$xoopsDB->prefix("kshop_orders_users")." WHERE orderid='$myval' ";
			$res=$xoopsDB->query($query);
			if(!$res) {
				echo "error: $query";
				return false;
			}

			//erase order
			$query = "Delete from ".$xoopsDB->prefix("kshop_orders")." WHERE id='$myval' ";
			$res=$xoopsDB->query($query);
			if(!$res) {
				echo "error: $query";
				return false;
			}
		}
		return true;
	}

	
	function sendStatusMail($oid){
	global $xoopsDB,$xoopsModuleConfig,$xoopsConfig;

	//Send email to client of status change
	//Load user info. client email is needed
	$user=$this->loadordUser($oid);

	//$stat=$this->LoadcStat($oid);

	$name=$user[1]['firstname'].' ' .$user[1]['lastname'];

	$xoopsMailer =& getMailer(); //Get mailer object
	$xoopsMailer->useMail(); // Set it to use email (as opposed to PM)

	$template= XOOPS_ROOT_PATH."/modules/kshop/language/".$xoopsConfig['language']."/mail_template/"; //path to template in users language
	if (!file_exists($template)){
	$template= XOOPS_ROOT_PATH."/modules/kshop/language/english/mail_template/"; //use english if no file found
	}

	$xoopsMailer->setTemplateDir($template);
	$xoopsMailer->setTemplate('status.tpl');
	$xoopsMailer->assign('USERNAME', $name);
	$xoopsMailer->assign('ORDERNUM', $oid);
	$xoopsMailer->assign('STATUS', $_POST['status']);
	$xoopsMailer->assign('COMMENT', $_POST['comment']);
	$xoopsMailer->assign('SHOP', $xoopsModuleConfig['shopname']);

	$xoopsMailer->setToEmails( array($user[1]['email'], $xoopsModuleConfig['mailorder']) );
	$xoopsMailer->setFromEmail($xoopsModuleConfig['mailcontact']);
	$xoopsMailer->setFromName($xoopsModuleConfig['shopname']);

	$subject=KS_STSUBTPL.$oid;
	$xoopsMailer->setSubject($subject);

	if ($xoopsMailer->send()) {
	$sendstat="send OK";
	}
	}
	


	function LoadcStat($oid=NULL){
		global $xoopsDB;
		$loader=array();
		if (isset($oid)){
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_orders_status')." WHERE orderid='$oid' ORDER BY date DESC ");
		} else {
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_orders_status')." ORDER BY date DESC ");
		}
		$i=1;
		while ($row = $xoopsDB->fetchArray($query)){
			$loader[$i]['oid'] = $row['orderid'];
			$loader[$i]['status'] = $row['status'];
			$loader[$i]['date'] = $row['date'];
			$loader[$i]['comment'] = $row['comment'];
			$i++;
		}
		return $loader;
	}

	function currentStatus(){
		global $xoopsDB,$xoopsUser;
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_status'). ' WHERE deff=1 ');
		$row = $xoopsDB->fetchArray($query);
		$deff = $row['status'];
		return $deff;
	}

	function loadStatus(){
		global $xoopsDB;
		$i=1;
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_status'));
		while ($row = $xoopsDB->fetchArray($query)){
			$loader[$i]["name"] = $row['status'];
			$loader[$i]["id"] = $row['sid'];
			$i++;
		}
		return $loader;
	}


	function loadordProd($oid){
		global $xoopsDB,$xoopsUser;
		$i=1;
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_orders_products')." WHERE order_id='$oid' ");
		while ($row = $xoopsDB->fetchArray($query)){
			$loader[$i]["qty"] = $row['qty'];
			$loader[$i]["p_num"] = $row['p_num'];
			$loader[$i]["p_name"] = $row['p_name'];
			$loader[$i]["p_price"] = $row['p_price'];
			$i++;
		}
		return $loader;
	}

	function loadordStatus($oid){
		global $xoopsDB;
		$loader=array();
		$i=1;
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_orders_status')." WHERE orderid='$oid' ORDER BY date DESC ");
		while ($row = $xoopsDB->fetchArray($query)){
			$loader[$i]["status"] = $row['status'];
			$loader[$i]["date"] = $row['date'];
			$loader[$i]["comment"] = $row['comment'];
			$i++;
		}
		return $loader;
	}

	function loadordUser($oid=NULL){
		global $xoopsDB;
		if (!isset($oid)) {
			$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_orders_users'));
		} else {
			$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_orders_users')." WHERE orderid='$oid' ");
		}
		$i=1;
		while ($row = $xoopsDB->fetchArray($query)){
			$loader[$i]["oid"] = $row['orderid'];
			$loader[$i]["firstname"] = $row['firstname'];
			$loader[$i]["lastname"] = $row['lastname'];
			$loader[$i]["company"] = $row['company'];
			$loader[$i]["address"] = $row['address'];
			$loader[$i]["zipcode"] = $row['zipcode'];
			$loader[$i]["city"] = $row['city'];
			$loader[$i]["country"] = $row['country'];
			$loader[$i]["tel"] = $row['tel'];
			$loader[$i]["fax"] = $row['fax'];
			$loader[$i]["email"] = $row['email'];
			$i++;
		}
		return $loader;
	}


	function loadOrder(){
		global $xoopsDB;
		$loader=array();
		$i=1;
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_orders')."");
		while ($row = $xoopsDB->fetchArray($query)){
			$loader[$i]["id"] = $row['id'];
			$loader[$i]["date"] = $row['date'];
			$loader[$i]["xid"] = $row['xuser'];
			$loader[$i]["prodtotal"] = $row['prodtotal'];
			$loader[$i]["comment"] = $row['comment'];
			$loader[$i]["payment"] = $row['paymethod'];
			$loader[$i]["shipping"] = $row['shipmethod'];
			$i++;
		}
		return $loader;
	}


	function loadAllOrders($usr=NULL){
		global $xoopsDB, $xoopsUser;

		$ouser=$this->loadordUser();
		$loadprod=$this->loadallProdDB();
		$loadstat=$this->LoadcStat();

		$loader=array();
		$i=1;
		if (isset($usr)){
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_orders') ." WHERE xuser='$usr'");
		} else {
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_orders'));
		}
		while ($row = $xoopsDB->fetchArray($query)){
			$loader[$i]["id"] = $row['id'];
			$loader[$i]["date"] = $row['date'];
			$loader[$i]["xid"] = $xoopsUser->uname($row['xuser']);
			$loader[$i]["prodtotal"] = $row['prodtotal'];
			$loader[$i]["numprod"] =$this->numberProducts($row['id']);

			//Load order status
			$q=1;
			foreach ($loadstat as $curr){
				if ($curr['oid']==$row['id']) {
					if ($q==1){
						$loader[$i]["currstat"] = $curr['status'];
					}
					$loader[$i]['stat'][$q]["date"] = $curr['date'];
					$loader[$i]['stat'][$q]["status"] = $curr['status'];
					$loader[$i]['stat'][$q]["comment"] = $curr['comment'];
					$q++;
				}
			}

			//Load products
			$q=1;
			foreach ($loadprod as $prod){
				if ($prod['oid']==$row['id']) {
					$loader[$i]['prod'][$q]["qty"] = $prod['qty'];
					$loader[$i]['prod'][$q]["num"] = $prod['p_num'];
					$loader[$i]['prod'][$q]["name"] = $prod['p_name'];
					$loader[$i]['prod'][$q]["price"] = $prod['p_price'];
					$q++;
				}
			}

			//Load user info
			foreach ($ouser as $user){
				if ($user['oid']==$row['id']){
					$loader[$i]["firstname"] = $user['firstname'];
					$loader[$i]["lastname"] = $user['lastname'];
					$loader[$i]["company"] = $user['company'];
					$loader[$i]["address"] = $user['address'];
					$loader[$i]["zipcode"] = $user['zipcode'];
					$loader[$i]["city"] = $user['city'];
					$loader[$i]["country"] = $user['country'];
					$loader[$i]["tel"] = $user['tel'];
					$loader[$i]["fax"] = $user['fax'];
					$loader[$i]["email"] = $user['email'];
				}
			}

			$loader[$i]["comment"] = $row['comment'];
			$loader[$i]["payment"] = $row['paymethod'];
			$loader[$i]["shipping"] = $row['shipmethod'];
			$i++;
		}

		return $loader;
	}

	function loadallProdDB($oid=NULL){
		global $xoopsDB;
		$i=1;
		if (isset($oid)){
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_orders_products') . " WHERE order_id='$oid'");
		} else {
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_orders_products'));
		}
		while ($row = $xoopsDB->fetchArray($query)){
			$loader[$i]["id"] = $row['id'];
			$loader[$i]["oid"] = $row['order_id'];
			$loader[$i]["qty"] = $row['qty'];
			$loader[$i]["p_num"] = $row['p_num'];
			$loader[$i]["p_name"] = $row['p_name'];
			$loader[$i]["p_price"] = $row['p_price'];
			$i++;
		}
		return $loader;
	}

	function numberProducts($id){
		global $xoopsDB;

		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_orders_products')." WHERE order_id=$id");
		$row = $xoopsDB->getRowsNum($query);
		return $row;
	}



}
?>