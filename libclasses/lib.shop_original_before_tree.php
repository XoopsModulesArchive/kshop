<?php

// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            	www.kaotik.biz
// |            	kaotik1 [at] gmail.com
// +--------------------------------------------------------------------------+



function loadCountry(){
	global $xoopsDB,$xoopsModuleConfig;
	$ctry=array();
	$q=1;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_country'));
	while ($row = $xoopsDB->fetchArray($query))
	{
		$ctry[$q]['id']=$row['cid'];
		$ctry[$q]['name']=$row['name'];
		if ($xoopsModuleConfig['defcountry']==$row['name']) $ctry[$q]['sel']='selected';
		$q++;
	}

	return $ctry;
}


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
		$cats[$q]['name']=$row['c_name'];
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

function buildBrands($id=NULL){
	global $xoopsDB;

	if (isset($id)){
		$q=1;
		$optsel=array();
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products_brands')." WHERE product_id='$id'");
		while ($row = $xoopsDB->fetchArray($query))
		{
			$optsel[$q]['id'] = $row['brand_id'];
			$q++;
		}
	}

	$q=1;
	$opt=array();
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_brands'));
	while ($row = $xoopsDB->fetchArray($query))
	{
		$opt[$q]['id'] = $row['mid'];
		$opt[$q]['name'] =$row['name'];
		if (isset($id)){
			foreach ($optsel as $opts){
				if ($opts['id']==$row['mid']){
					$opt[$q]['sel']='selected';
				}
			}
		}
		$q++;
	}
	return $opt;
}

//Delete brands from DB and from associated products
function delBrand($brands){
	global $xoopsDB;

	// This will delete 1 or several options selected
	foreach ($brands as $brand) {
		//Delete from options table.
		$query = "Delete from ".$xoopsDB->prefix("kshop_brands")." where mid='$brand'";
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error: $query";
		}
		//Delete from any product that's linked to it.
		$query = "Delete from ".$xoopsDB->prefix("kshop_products_brands")." where brand_id='$brand'";
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error: $query";
		}
	}
	redirect_header('products_brands.php', 1, KS_DELBRNDSUCESS);
	die();
}

//Insert option into DB
function insertBrand($brand){
	global $xoopsDB;
	$myts = myTextSanitizer::getInstance();
	//$brand = $myts->addslashes($brand);
	$query = "Insert into ".$xoopsDB->prefix("kshop_brands")." (name) values ('$brand')";
	$res=$xoopsDB->query($query);
	if(!$res) {
		echo "error: $query";
	}

	redirect_header('products_brands.php', 1, KS_INSRTBRNDSUCESS);
	die();
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

function prodOptions($id){
	global $xoopsDB;

	$q=1;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products_options')." WHERE product_id='$id'");
	while ($row = $xoopsDB->fetchArray($query))
	{
		$optsel[$q]['oid'] = $row['options_id'];
		$q++;
	}

	$q=1;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_options'));
	while ($row = $xoopsDB->fetchArray($query))
	{
		foreach ($optsel as $opt){
			if ($opt['oid']==$row['id']){
				$options[$q]['oid']=$row['id'];
				$options[$q]['name']=$row['options'];
				$q++;
			}
		}
	}
	return $options;
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
* Build category tree. I've recoded this function so that it's queries go through xoops. I also removed the query from inside a loop, which is bad coding. Categories box now indents subcategories with '--'. Unfortunatly I haven't been able to go beyond 1 level of subcategories.....yet.
* $parent  This tell which category to set as main. By default it's 1
* $pcats   This defines which categories are preselected.
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
		$cats[$q]['name']=$row['c_name'];

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

/* ----------------------------------------------
//Load all products from a selected category.
//------------------------------------------------*/
function ldprodperCat($id){
	global $xoopsDB;

	// Load only specials routine:
	$q=1;
	if ($id==999){
		$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products'). " WHERE p_has_special=1 ");
		while ($myrow = $xoopsDB->fetchArray($query)){
			if ($myrow['p_sp_price']!=0){
				$prod[$q]['id']=$myrow['p_id'];
				$prod[$q]['name']=$myrow['p_name'];
				$prod[$q]['desc']=$myrow['p_desc'];
				$prod[$q]['thumb']=$myrow['p_thumb'];
				$prod[$q]['price']=BuildPrice($myrow['p_price']);
				$prod[$q]['sprice']=BuildPrice($myrow['p_sp_price']);
				$prod[$q]['show_me']=$myrow['p_show_me'];
				$prod[$q]['show_img']=$myrow['p_show_img'];
				if (($myrow['p_sp_price']!='0') AND ($myrow['p_has_special']==1)) $prod[$q]['hasspec']=1;
				$q++;
			}
		}
		return $prod;
	}


	$txt='';
	$q=1;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products_categories') . " WHERE category_id='$id' ");
	while ($myrow = $xoopsDB->fetchArray($query)){
		$pcat[$q]['id']=$myrow['product_id'];
		$q++;
	}
	if (empty($pcat)) return $txt;

	$q=1;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products'));
	while ($myrow = $xoopsDB->fetchArray($query)){
		foreach ($pcat as $cat){
			if ($cat['id']==$myrow['p_id']){
				$prod[$q]['id']=$myrow['p_id'];
				$prod[$q]['name']=$myrow['p_name'];
				$prod[$q]['desc']=$myrow['p_desc'];
				$prod[$q]['thumb']=$myrow['p_thumb'];
				$prod[$q]['price']=BuildPrice($myrow['p_price']);
				$prod[$q]['sprice']=BuildPrice($myrow['p_sp_price']);
				$prod[$q]['show_me']=$myrow['p_show_me'];
				$prod[$q]['show_img']=$myrow['p_show_img'];
				if (($myrow['p_sp_price']!='0') AND ($myrow['p_has_special']==1)) $prod[$q]['hasspec']=1;
				$q++;
			}
		}
	}
	return $prod;
}

function loadProdinBrand($id){
	global $xoopsDB;

	$q=1;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products_brands') . " WHERE brand_id='$id' ");
	while ($myrow = $xoopsDB->fetchArray($query)){
		$brands[$q]['id']=$myrow['product_id'];
		$q++;
	}
	

	$q=1;
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products'));
	while ($myrow = $xoopsDB->fetchArray($query)){
		foreach ($brands as $brand){
			if ($brand['id']==$myrow['p_id']){
				$prod[$q]['id']=$myrow['p_id'];
				$prod[$q]['name']=$myrow['p_name'];
				$prod[$q]['desc']=$myrow['p_desc'];
				$prod[$q]['thumb']=$myrow['p_thumb'];
				$prod[$q]['price']=BuildPrice($myrow['p_price']);
				$prod[$q]['sprice']=BuildPrice($myrow['p_sp_price']);
				$prod[$q]['show_me']=$myrow['p_show_me'];
				$prod[$q]['show_img']=$myrow['p_show_img'];
				if (($myrow['p_sp_price']!='0') AND ($myrow['p_has_special']==1)) $prod[$q]['hasspec']=1;
				$q++;
			}
		}
	}

return $prod;
}

/*-------------------------
Load all products
//------------------------------*/
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

// Load a specific product
function loadxProd($id){
	global $xoopsDB,$CART;

	$prod=array();

	//-- Load up the information for the product.
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products') . " WHERE p_id = '$id' ");
	$myrow = $xoopsDB->fetchArray($query);

	$prod['id']=$myrow['p_id'];
	$prod['item_nr']=$myrow['p_item_nr'];
	$prod['name']=$myrow['p_name'];
	$prod['desc']=$myrow['p_desc'];
	$prod['desclong']=$myrow['p_desc_long'];
	$prod['p_style']=$myrow['p_style'];
	$prod['p_thumb']=$myrow['p_thumb'];
	$prod['p_image']=$myrow['p_image'];
	$prod['price']=$myrow['p_price'];
	$prod['sp_price']=$myrow['p_sp_price'];
	$prod['tax']=$myrow['p_tax'];
	$prod['show_me']=$myrow['p_show_me'];
	$prod['show_img']=$myrow['p_show_img'];
	$prod['has_special']=$myrow['p_has_special'];
	$prod['has_style']=$myrow['p_has_style'];
	$prod['modified']=$myrow['p_modified'];
	$prod['added']=$myrow['p_added'];

	//Load option for this product
	if (isset($CART->options[$id])){
		foreach ($CART->options as $key => $value){
			if ($key!=$id) continue;
			$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_options') . " WHERE id = '$value' ");
			$myrow = $xoopsDB->fetchArray($query);
			$prod['option']=$myrow['options'];
		}
	}

	return $prod;
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
	global $CFG,$xoopsTpl,$xoopsDB,$xoopsModuleConfig;
	$specials=true;
	$newstuff=true;
	$qty=$xoopsModuleConfig['numspecial'];
	$days=40;

	//-- Calculate new timestamp: 1 day = 86400 seconds.
	//-- The number of days which should be deducted from current date.
	$daysback = date("YmdHis", time() - $days*86400);
	/*
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
	*/
	$where_clause = "p_show_me = 1 ";

	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_products')." WHERE $where_clause ORDER BY RAND() LIMIT $qty ");
	$i=1;
	$totspec=array();
	while ($row = $xoopsDB->fetchArray($query))
	{
		$totspec[$i]['id']=$row['p_id'];
		$totspec[$i]['image']=$row['p_thumb'];
		$totspec[$i]['name']=$row['p_name'];
		$totspec[$i]['price']=BuildPrice($row['p_price']);
		$totspec[$i]['sprice']=BuildPrice($row['p_sp_price']);
		(($row['p_has_special']==1) && (!empty($row['p_sp_price']))) ? $totspec[$i]['special']=1 : $totspec[$i]['special']=0;
		$i++;
	}

	$xoopsTpl->assign('totspec', $totspec);
	$xoopsTpl->assign('colnum', $xoopsModuleConfig['colpercat']);

}

?>