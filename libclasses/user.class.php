<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

class Kuser
{
	var $user; //This is used by SESSION to keep the data available even if user switches pages.
	var $userdata =array(); //User data gets loaded into here


	// Checks which fields are mandatory (controled by module prefernce), then checks if they are empty. If yes then set an error message
	function checkstep1Errors(){
		global $xoopsModuleConfig;

		$error=FALSE;
		if (($xoopsModuleConfig['reqfirstname']) && (empty($_POST['firstname']))) $error['firstname']=KS_REQFRSTNAME;
		if (($xoopsModuleConfig['reqlastname']) && (empty($_POST['lastname']))) $error['lastname']=KS_REQLSTNAME;
		if (($xoopsModuleConfig['reqcompany']) && (empty($_POST['company']))) $error['company']=KS_REQCOMPANY;
		if (($xoopsModuleConfig['reqstreet']) && (empty($_POST['address']))) $error['address']=KS_REQADRSS;
		if (($xoopsModuleConfig['reqzip']) && (empty($_POST['zipcode']))) $error['zip']=KS_REQZIP;
		if (($xoopsModuleConfig['reqcity']) && (empty($_POST['city']))) $error['city']=KS_REQCITY;
		if (($xoopsModuleConfig['reqcountry']) && (empty($_POST['country']))) $error['country']=KS_REQCOUNTRY;
		if (($xoopsModuleConfig['reqtelefone']) && (empty($_POST['tel']))) $error['tel']=KS_REQTEL;
		if (($xoopsModuleConfig['reqfax']) && (empty($_POST['fax']))) $error['fax']=KS_REQFAX;
		if (empty($_POST['email'])) $error['email']=KS_REQEMAIL;
		if (($xoopsModuleConfig['reqcomments']) && (empty($_POST['comment']))) $error['comments']=KS_REQCOMMENT;

		if (!$error) $this->saveUserData();

		return $error;
	}

	//Mark fields as mandatory using $sign as the symbol
	function reqFields(){
		global $xoopsModuleConfig,$xoopsTpl;

		$sign='*';

		if ($xoopsModuleConfig['reqfirstname']) $xoopsTpl->assign('req1', $sign);
		if ($xoopsModuleConfig['reqlastname']) $xoopsTpl->assign('req2', $sign);
		if ($xoopsModuleConfig['reqcompany']) $xoopsTpl->assign('req3', $sign);
		if ($xoopsModuleConfig['reqstreet']) $xoopsTpl->assign('req4', $sign);
		if ($xoopsModuleConfig['reqzip']) $xoopsTpl->assign('req5', $sign);
		if ($xoopsModuleConfig['reqcity']) $xoopsTpl->assign('req6', $sign);
		if ($xoopsModuleConfig['reqcountry']) $xoopsTpl->assign('req7', $sign);
		if ($xoopsModuleConfig['reqtelefone']) $xoopsTpl->assign('req8', $sign);
		if ($xoopsModuleConfig['reqfax']) $xoopsTpl->assign('req9', $sign);
		if ($xoopsModuleConfig['reqcomments']) $xoopsTpl->assign('req10', $sign);
	}


	/* ----------------------------
	LOAD USER INFO
	First check if there is any info in the SESSION, if yes use that. Now check if user is logged in. If NOT then leave empty. If user is logged in check if this is first time ordering with kshop. If NO get the info from the DB.
	//-------------------------------------*/
	function LoadUserData()
	{
		global $xoopsUser, $USER, $xoopsDB;
		
		//Display a * for all required fields
		$this->reqFields();
		
		//if user session is already set, use that info.
		if (isset($USER)){
			return $USER;
		}

		//$xid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : KS_ANONYMS;
		if (!is_object($xoopsUser)){
			return;
		}
		$xid = $xoopsUser->getVar('uid');
		//check if user has used kshop before.
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_users')." WHERE xid='$xid' ");
		$row = $xoopsDB->fetchArray($query);

		//User is logged in and has used kshop before.
		if (!empty($row)){
			$userdata['firstname']=$row['firstname'];
			$userdata['lastname']=$row['lastname'];
			$userdata['company']=$row['company'];
			$userdata['address']=$row['address'];
			$userdata['zipcode']=$row['zipcode'];
			$userdata['city']=$row['city'];
			$userdata['country']=$row['country'];
			$userdata['tel']=$row['tel'];
			$userdata['fax']=$row['fax'];
			$userdata['email']=$row['email'];
		} else {
			$userdata='';
		}

		return $userdata;
	}


	// Save user info to SESSION
	function saveUserData()
	{
		global $USER;

		$USER['firstname'] = $_POST['firstname'];
		$USER['lastname']  = $_POST['lastname'];
		$USER['company']   = $_POST['company'];
		$USER['address']   = $_POST['address'];
		$USER['zipcode']   = $_POST['zipcode'];
		$USER['city']     = $_POST['city'];
		$USER['country']  = $_POST['country'];
		$USER['tel']       = $_POST['tel'];
		$USER['fax']       = $_POST['fax'];
		$USER['email']     = $_POST['email'];
		$USER['comment']   = $_POST['comment'];
	}


	//Save user info to DB
	function saveUsertoDB(){
		global $USER,$xoopsDB,$xoopsUser;

		$xid = is_object($xoopsUser) ? $xoopsUser->getVar('uid') : 0;

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

		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_users')." WHERE xid='$xid' ");
		$row = $xoopsDB->fetchArray($query);

		//User is logged in and has used kshop before.
		if (!empty($row)){
			$this->updateUserDB($xid);
			return;
		}

		$query = "Insert into ".$xoopsDB->prefix("kshop_users")." (xid, firstname, lastname, company, address, zipcode, city, country, tel, fax, email)
		values 
		('$xid','$firstname','$lastname','$company','$address','$zipcode','$city','$country','$tel','$fax','$email')";
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error";
		}
	}


	function updateUserDB($xid){
		global $USER,$xoopsDB;

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

		$query = "UPDATE ".$xoopsDB->prefix("kshop_users")." SET
		firstname = '$firstname', 
		lastname = '$lastname', 
		company = '$company', 
		address = '$address', 
		zipcode = '$zipcode', 
		city = '$city', 
		country = '$country', 
		fax = '$fax', 
		tel = '$tel', 
		email = '$email'  
    WHERE xid = '$xid'";

		$res=$xoopsDB->query($query);
		if(!$res) {
			return TRUE;
		}
	}

	function saveUserOrder($orderid){
		global $USER,$xoopsDB;

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


		$query = "Insert into ".$xoopsDB->prefix("kshop_orders_users")." (orderid, firstname, lastname, company, address, zipcode, city, country, tel, fax, email)
		values 
		('$orderid', '$firstname','$lastname','$company','$address','$zipcode','$city','$country','$tel','$fax','$email')";

		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error";
		}

		$this->saveUsertoDB();
	}





	function setErrors($error)
	{

	}

	function setMessage($mess)
	{

	}

}

?>