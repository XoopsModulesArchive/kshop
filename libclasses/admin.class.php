<?php

// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            	www.kaotik.biz
// |            	kaotik1 [at] gmail.com
// +--------------------------------------------------------------------------+

function loadCat($id){
	global $xoopsDB;

	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_categories') . " WHERE c_id = '$id' ");
	$row = $xoopsDB->fetchArray($query);


	$cat=array();
	$cat['id']=$row['c_id'];
	$cat['parent']=$row['c_parent_id'];
	$cat['name']=$row['c_name'];
	$cat['desc']=$row['c_description'];
	$cat['order']=$row['c_order'];
	$cat['img']=$row['c_image'];
	$cat['showimg']=$row['c_show_image'];

	return $cat;
}


function listCats(){
	global $xoopsDB;

	$q=1;
	$cats=array();
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_categories'));
	while ($row = $xoopsDB->fetchArray($query))
	{
		$cats[$q]['id']=$row['c_id'];
		if ($row['c_id']==1){
			$cats[$q]['name']=KS_HOME;
		} else {
			$cats[$q]['name']=$row['c_name'];
		}
		$cats[$q]['parent']=$row['c_parent_id'];
		$cats[$q]['sort']=$row['c_order'];
		$q++;
	}

	$cattemp=array();
	$cattemp=$cats;


	foreach ($cats as $cat){
		foreach ($cattemp as $ctemp){
			if ($cat['parent']==$ctemp['id']){
				$cat['parent']=$ctemp['name'];
			}
		}
	}


	return $cats;
}


//Delete options from DB and from associated products
function delOption($options){
	global $xoopsDB;

	// This will delete 1 or several options selected
	foreach ($options as $option) {
		//Delete from options table.
		$query = "Delete from ".$xoopsDB->prefix("kshop_options")." where id='$option'";
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error: $query";
		}
		//Delete from any product that's linked to it.
		$query = "Delete from ".$xoopsDB->prefix("kshop_products_options")." where options_id='$option'";
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error: $query";
		}
	}
	redirect_header('products_options.php', 1, KS_DELOPTNSUCESS);
	die();
}

//Insert option into DB
function insertOption($option){
	global $xoopsDB;
	$myts = myTextSanitizer::getInstance();
	$option = $myts->addslashes($option);
	$query = "Insert into ".$xoopsDB->prefix("kshop_options")." (options) values ('$option')";
	$res=$xoopsDB->query($query);
	if(!$res) {
		echo "error: $query";
	}

	redirect_header('products_options.php', 1, KS_INSRTOPTNSUCESS);
	die();
}

function buildOptions($id=NULL)
{
	global $xoopsDB;

	if (isset($id)){
		$q=1;
		$optsel=array();
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products_options')." WHERE product_id='$id'");
		while ($row = $xoopsDB->fetchArray($query))
		{
			$optsel[$q]['oid'] = $row['options_id'];
			$q++;
		}
	}

	$q=1;
	$opt=array();
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_options'));
	while ($row = $xoopsDB->fetchArray($query))
	{
		$opt[$q]['id'] = $row['id'];
		$opt[$q]['name'] =$row['options'];
		if (isset($id)){
			foreach ($optsel as $opts){
				if ($opts['oid']==$row['id']){
					$opt[$q]['sel']='selected';
				}
			}
		}
		$q++;
	}
	return $opt;
}

//Load categories for selected product
function loadCatsPerProd($id){
	global $xoopsDB;

	$q=1;
	$cats=array();
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_categories'));
	while ($row = $xoopsDB->fetchArray($query))
	{
		$cats[$q]['id']=$row['c_id'];
		$cats[$q]['name']=$row['c_name'];
		$q++;
	}

	$pcat=array();
	$q=1;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products_categories')." WHERE product_id='$id'");
	while ($row = $xoopsDB->fetchArray($query))
	{
		foreach ($cats as $cat){
			if ($cat['id']!=$row['category_id']) continue;
			$pcat[$q]['id']=$cat['id'];
			$pcat[$q]['name']=$cat['name'];
			$q++;
		}
	}

	return $pcat;
}


/**
* Build category tree. I've recoded this function so that it's queries go through xoops. I also removed the query from inside a loop, which is bad coding. Categories box now indents subcategories with '--'. Unfortunatly I haven't been able to go beyond 1 level of subcategories.....yet
*/

function buildCategoryTree($parent=1,$pcats=NULL)
{
	global $xoopsDB;

	$allcats=array();;
	$q=1;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_categories'));
	while ($row = $xoopsDB->fetchArray($query))
	{
		$allcats[$q]['id'] = $row['c_id'];
		$allcats[$q]['name'] = $row['c_name'];
		$allcats[$q]['parent'] = $row['c_parent_id'];
		$q++;
	}

	$cats=array();;
	$q=1;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_categories'). " WHERE c_parent_id='$parent'");
	while ($row = $xoopsDB->fetchArray($query))
	{
		$cats[$q]['id']=$row['c_id'];

		if ($row['c_id']==1){
			$cats[$q]['name']=KS_HOME;
		} else {
			$cats[$q]['name']=$row['c_name'];
		}

		if (isset($pcats)){
			foreach ($pcats as $pcat){
				if ($pcat['id']==$row['c_id']){
					$cats[$q]['sel']='selected';
				}
			}
		}
		foreach ($allcats as $allcat){
			if ($allcat['parent']==$parent) continue;
			if ($allcat['parent']!=$row['c_id']) continue;
			$q++;
			$cats[$q]['id']=$allcat['id'];
			$cats[$q]['name']='--'.$allcat['name'];
			if (!isset($pcats)) continue;
			foreach ($pcats as $pcat){
				if ($pcat['id']!=$allcat['id']) continue;
				$cats[$q]['sel']='selected';
			}
		}
		$q++;
	}

	return $cats;
}

function loadallProd(){
	global $xoopsDB;

	$q=1;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_categories'));
	while ($myrow = $xoopsDB->fetchArray($query)){
		$cats[$q]['id']=$myrow['c_id'];
		$cats[$q]['name']=$myrow['c_name'];
		$cats[$q]['parent']=$myrow['c_parent_id'];
		$q++;
	}



	$q=1;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products_categories'));
	while ($myrow = $xoopsDB->fetchArray($query)){
		$cps[$q]['pid']=$myrow['product_id'];
		$cps[$q]['cid']=$myrow['category_id'];
		$q++;
	}

	$prod=array();
	$q=1;
	//-- Load up the information for the product.
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products'));
	while ($myrow = $xoopsDB->fetchArray($query)){
		$prod[$q]['id']=$myrow['p_id'];
		$prod[$q]['p_item_nr']=$myrow['p_item_nr'];
		$prod[$q]['p_name']=$myrow['p_name'];
		$prod[$q]['p_desc']=$myrow['p_desc'];
		$prod[$q]['p_desc_long']=$myrow['p_desc_long'];
		$prod[$q]['p_style']=$myrow['p_style'];
		$prod[$q]['p_thumb']=$myrow['p_thumb'];
		$prod[$q]['p_image']=$myrow['p_image'];
		$prod[$q]['p_price']=$myrow['p_price'];
		$prod[$q]['p_sp_price']=$myrow['p_sp_price'];
		$prod[$q]['p_tax']=$myrow['p_tax'];
		$prod[$q]['p_show_me']=$myrow['p_show_me'];
		$prod[$q]['p_show_img']=$myrow['p_show_img'];
		$prod[$q]['p_has_special']=$myrow['p_has_special'];
		$prod[$q]['p_has_style']=$myrow['p_has_style'];
		$prod[$q]['p_modified']=$myrow['p_modified'];
		$prod[$q]['p_added']=$myrow['p_added'];

		foreach ($cps as $cp){
			if ($cp['pid']!=$myrow['p_id']) continue;
			foreach ($cats as $cat){
				if ($cp['cid']!=$cat['id']) continue;
				$prod[$q]['catid']=$cat['id'];
				$prod[$q]['catname']=$cat['name'];
				$prod[$q]['parent']=$cat['parent'];
			}
		}
		$q++;
	}
	return $prod;
}

// will replace loadProduct
function loadxProd($id){
	global $xoopsDB;

	$prod=array();

	//-- Load up the information for the product.
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products') . " WHERE p_id = '$id' ");
	$myrow = $xoopsDB->fetchArray($query);

	$prod['p_item_nr']=$myrow['p_item_nr'];
	$prod['p_name']=$myrow['p_name'];
	$prod['p_desc']=$myrow['p_desc'];
	$prod['p_desc_long']=$myrow['p_desc_long'];
	$prod['p_style']=$myrow['p_style'];
	$prod['p_thumb']=$myrow['p_thumb'];
	$prod['p_image']=$myrow['p_image'];
	$prod['p_price']=$myrow['p_price'];
	$prod['p_sp_price']=$myrow['p_sp_price'];
	$prod['p_tax']=$myrow['p_tax'];
	$prod['p_show_me']=$myrow['p_show_me'];
	$prod['p_show_img']=$myrow['p_show_img'];
	$prod['p_has_special']=$myrow['p_has_special'];
	$prod['p_has_style']=$myrow['p_has_style'];
	$prod['p_modified']=$myrow['p_modified'];
	$prod['p_added']=$myrow['p_added'];

	return $prod;
}


/**
* Load up the product details for a product with id $id and return it as
* an object, or false if the object couldn't be loaded (i.e. it's not found).
*/
function loadProduct($id)
{
	$table1 = DB_PREFIX .'products p';
	$table2 = DB_PREFIX .'products_categories pc';

	$sql = db_query("SELECT p.p_id,
                            p.p_item_nr,
                            p.p_name,
                            p.p_desc,
                            p.p_desc_long,
                            p.p_image,
                            p.p_price,
                            p.p_show_img,
                            p.p_sp_price,
                            p.p_has_special,
                            p.p_has_style,
                            pc.category_id
                     FROM   $table1, $table2
                     WHERE  p.p_id = pc.product_id
                     AND    p.p_id = $id
                    ");

	if ($sql)
	{
		return db_fetch_object($sql);
	}
	else
	{
		return false;
	}
}


/**
* Returns a query of all the items in the shopping cart.
*/
function getCartItems()
{
	global $CART;

	$in_clause = $CART->get_productid_list();

	if (empty($in_clause))
	{
		return false;
	}

	return db_query("SELECT p_id,
                            p_name,
                            p_item_nr,
                            p_price,
                            p_sp_price,
                            p_style,
                            p_has_special
                     FROM   ". DB_PREFIX ."products
                     WHERE  p_id
                     IN    ($in_clause)"
	);
}


/**
* This function saves the order information into the session variable
* $USER['orderinfo']. It is used in the purchase confirmation stage.
*/
function saveOrderInfo(&$frm)
{
	global $USER;

	$order = new Object();

	$order->firstname = $frm['firstname'];
	$order->lastname  = $frm['lastname'];
	$order->company   = $frm['company'];
	$order->street    = $frm['street'];
	$order->zip       = $frm['zip'];
	$order->city      = $frm['city'];
	$order->country   = $frm['country'];
	$order->tel       = $frm['tel'];
	$order->fax       = $frm['fax'];
	$order->email     = $frm['email'];
	$order->comment   = $frm['comment'];

	$USER['orderinfo'] =& $order;
}


/**
* This function is the counterpart to save_orderinfo. It is used to
* retrieve the order information in the complete order page.
*/
function loadOrderInfo()
{
	global $USER;

	if (empty($USER['orderinfo']))
	{
		return false;
	}
	else
	{
		return $USER['orderinfo'];
	}
}


/**
* This function is called to clear the orderinfo session variable, it should
* be used after an order was successfully completed.
*/
function clearOrderInfo()
{
	global $USER;

	unset($USER['orderinfo']);
}


/**
* Show items where the special- and/or news-flag is set.
*
* Arguments (values are set from config.inc.php):
* $specials = true/false
* $newstuff = true/false
* $qty = number of items which should be displayed.
* $days = number of days which should be deducted from current date.
*
* Display of items is random (via RAND()).
*/
function showSpecials()
{
	global $CFG,$xoopsTpl,$xoopsDB;
	$specials=true;
	$newstuff=true;
	$qty=5;
	$days=40;

	//-- Calculate new timestamp: 1 day = 86400 seconds.
	//-- The number of days which should be deducted from current date.
	$daysback = date("YmdHis", time() - $days*86400);

	//-- Load items where special- or news-flag is set.
	if ($specials && $newstuff)
	{
		$where_clause = "p_has_special = 1 OR p_modified > $daysback";
	}
	elseif ($specials)
	{
		$where_clause = "p_has_special = 1";
	}
	elseif ($newstuff)
	{
		$where_clause = "p_modified > $daysback";
	}
	//Temp CLAUSE-------------------------------------------------------
	$where_clause = "p_has_special = 0";

	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products')."  ORDER BY RAND() LIMIT $qty ");
	$i=1;
	$totspec=array();
	while ($row = $xoopsDB->fetchArray($query))
	{
		$totspec[$i]['image']=$CFG->productsdir .'/'. $row['p_thumb'];
		$totspec[$i]['link']=$CFG->wwwroot .'/product_details.php?id='.$row['p_id'];
		$i++;
	}
	$colnum=3;
	$xoopsTpl->assign('totspec', $totspec);
	$xoopsTpl->assign('colnum', $colnum);

	$showspecials=1;
	if ($showspecials==1){
		$xoopsTpl->assign('incspecial', '1');
	} else {
		$xoopsTpl->assign('incspecial', '0');
	}

}


?>