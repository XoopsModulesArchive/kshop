<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

include 'admin_header.php';

xoops_cp_header();

$mainTabs->setCurrent('products', 'tabs');
$mainTabs->display();


(!isset($_REQUEST['mode'])) ? $sel='' : $sel=$_REQUEST['mode'];

switch ($sel)
{
	case "add" :
	addProduct($_GET['category_id']);
	break;

	case "edit" :
	editProduct($_REQUEST['idP']);
	break;

	case "del" :
	deleteProduct($_REQUEST['idP']);
	printList();
	break;

	case "insert" :
	insertProduct($_REQUEST['id'], $_POST);
	printList();
	break;

	case "update" :
	updateProduct($_REQUEST['id'], $_POST);
	printList();
	break;

	case "psearch" :
	printSearchResult($_POST['search']);
	break;

	default :
	printList();
	break;
}


// Print a blank product form so we can add a new product.
function addProduct($cid = 1)
{
	global $xoopsTpl;
	
$scat=array();
$scat[1]['id']=$cid;
//-- Build the categories listbox options.
$cats=buildCategoryTree(1,$scat);
$xoopsTpl->assign('cats', $cats);

$xoopsTpl->assign('mode', 'insert');

$editor=getEditor('p_desc', 'p_desc', '', '100%', '100px');
$xoopsTpl->assign('shorttext', $editor);

$editor=getEditor('p_desc_long', 'p_desc_long', '', '100%', '300px');
$xoopsTpl->assign('longtext', $editor);

// Build options box
$options=buildOptions();
$xoopsTpl->assign('options', $options);

//build brands dropdown
$brands=buildBrands();
$xoopsTpl->assign('brands', $brands);


$xoopsTpl->display('db:admin_products.html');
}


// Print a form so we can edit the selected product.
function editProduct($id)
{
global $xoopsTpl;


$catsel=loadCatsPerProd($id);

//-- Build the categories listbox options.
$cats=buildCategoryTree(1,$catsel);
$xoopsTpl->assign('cats', $cats);

$xoopsTpl->assign('mode', 'update');
$xoopsTpl->assign('prodid', $id);


// Build options box
$options=buildOptions($id);
$xoopsTpl->assign('options', $options);

//build brands dropdown
$brands=buildBrands($id);
$xoopsTpl->assign('brands', $brands);


$prod=loadxProd($id);
$xoopsTpl->assign('p_item_nr', $prod['item_nr']);
$xoopsTpl->assign('p_name', $prod['name']);
$xoopsTpl->assign('p_price', $prod['price']);
$xoopsTpl->assign('p_sp_price', $prod['sp_price']);
$xoopsTpl->assign('p_show_mecheck', printChecked($prod['show_me']));
$xoopsTpl->assign('p_show_imgcheck', printChecked($prod['show_img']));
$xoopsTpl->assign('p_has_specialcheck', printChecked($prod['has_special']));
$xoopsTpl->assign('p_has_stylecheck', printChecked($prod['has_style']));
$xoopsTpl->assign('p_image', $prod['p_image']);
$xoopsTpl->assign('p_thumb', $prod['p_thumb']);

$editor2=getEditor('p_desc','p_desc', $prod['desc'], '97%', '200px');
$xoopsTpl->assign('shorttext', $editor2);

$editor1=getEditor('p_desc_long','p_desc_long', $prod['desclong'], '97%', '200px');
$xoopsTpl->assign('longtext', $editor1);


$xoopsTpl->display('db:admin_products.html');
}


/**
* Delete the product specified by $id. We have to delete the product and then
* the appropriate entries from the products_categories table. This should be
* wrapped inside a transaction, unfortunately MySQL currently does not support
* transactions.
*/
function deleteProduct($id)
{
	global $xoopsDB,$xoopsModule;
	

	$query = "delete from ".$xoopsDB->prefix("kshop_products")." where p_id='$id' ";
	$res=$xoopsDB->queryF($query);
	if(!$res) {
		echo "error: $query <br>";
	}


	$query = "DELETE FROM ".$xoopsDB->prefix("kshop_products_categories")." WHERE product_id='$id'";
	$res=$xoopsDB->queryF($query);
	if(!$res) {
		echo "error: $query <br>";
	}
	
	$query = "DELETE FROM ".$xoopsDB->prefix("kshop_products_options")." WHERE product_id='$id'";
	$res=$xoopsDB->queryF($query);
	if(!$res) {
		echo "error: $query <br>";
	}
	
	$query = "DELETE FROM ".$xoopsDB->prefix("kshop_products_brands")." WHERE product_id='$id'";
	$res=$xoopsDB->queryF($query);
	if(!$res) {
		echo "error: $query <br>";
	}


xoops_comment_delete($xoopsModule->getVar('mid'), $id);
	
redirect_header('products.php', 1, KS_DELSUCESS);
die();
}


/**
* Add a new product under the parent $id.
* All the fields that we want are going to be in the variable $frm.
*/
function insertProduct($id, $frm)
{
	global $xoopsDB;

	//-- Checkbox activated?
	checkChecked($_POST['p_show_me']);
	checkChecked($_POST['p_show_img']);
	checkChecked($_POST['p_has_special']);
	checkChecked($_POST['p_has_style']);

	//-- Add the product into the products table.
	
$thumb=createThumb($_POST[p_image]);

	$query = "Insert into ".$xoopsDB->prefix("kshop_products")."
					(p_item_nr,
                      p_name,
                      p_desc,
                      p_desc_long,
                      p_thumb,
                      p_image,
                      p_price,
                      p_sp_price,
                      p_tax,
                      p_show_me,
                      p_show_img,
                      p_has_special,
                      p_has_style)
	VALUES ('$_POST[p_item_nr]',
                      '$_POST[p_name]',
                      '$_POST[p_desc]',
                      '$_POST[p_desc_long]',
                      '$thumb',
                      '$_POST[p_image]',
                      '$_POST[p_price]',
                      '$_POST[p_sp_price]',
                      '$_POST[p_tax]',
                      '$_POST[p_show_me]',
                      '$_POST[p_show_img]',
                      '$_POST[p_has_special]',
                      '$_POST[p_has_style]')";


	$res=$xoopsDB->query($query);
	if(!$res) {
		echo "error: $query";
	}

	//-- get the product id that was just created.
	$product_id = mysql_insert_id();

	//-- Add this product under the specified categories.
	for ($i = 0; $i < count($frm['categories']); $i++)
	{
		$query = "Insert into ".$xoopsDB->prefix("kshop_products_categories")." (product_id, category_id) values ('$product_id','{$frm['categories'][$i]}')";
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error: $query<br>";
		}
	}

	$cme= count($_POST['p_options']);
	
	//Add options to this product.
	for ($i = 0; $i < $cme; $i++)
	{
		$query = "Insert into ".$xoopsDB->prefix("kshop_products_options")." (product_id, options_id) values ('$product_id','{$_POST['p_options'][$i]}')";
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error: $query<br>";
		}
	}

	//Insert brand for this product
	$query = "Insert into ".$xoopsDB->prefix("kshop_products_brands")." (product_id, brand_id) values ('$product_id','{$_POST['brands']}')";
	$res=$xoopsDB->query($query);
	if(!$res) {
	echo "error: $query<br>";
	}

redirect_header('products.php', 1, KS_CREATSUCESS);
die();
}


/**
* Update the product $id with new values.
* All the fields that we want are going to in the variable $frm.
*/
function updateProduct($id, $frm)
{
	global $xoopsDB;
	
$thumb=createThumb($frm['p_image']);


	//-- Checkbox activated?
	(isset($frm['p_show_me'])) ? $frm['p_show_me']=1 : $frm['p_show_me']=0;
	(isset($frm['p_show_img'])) ? $frm['p_show_img']=1 : $frm['p_show_img']=0;
	(isset($frm['p_has_special'])) ? $frm['p_has_special']=1 : $frm['p_has_special']=0;
	(isset($frm['p_has_style'])) ? $frm['p_has_style']=1 : $frm['p_has_style']=0;
	
	
	//-- Update the products table with the new information.
	$query = "UPDATE ".$xoopsDB->prefix("kshop_products")."
       				  SET     p_item_nr = '". $frm['p_item_nr'] ."',
                      p_name = '". $frm['p_name'] ."',
                      p_desc = '". $frm['p_desc'] ."',
                      p_desc_long = '". $frm['p_desc_long'] ."',
                      p_thumb = '". $thumb ."',
                      p_image = '". $frm['p_image'] ."',
                      p_price = '". $frm['p_price'] ."',
                      p_sp_price = '". $frm['p_sp_price'] ."',
                      p_show_me = '". $frm['p_show_me'] ."',
                      p_show_img = '". $frm['p_show_img'] ."',
                      p_has_special = '". $frm['p_has_special'] ."',
                      p_has_style = '". $frm['p_has_style'] ."'
              WHERE   p_id = '$id'";
			  
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error";
		}

	//-- Delete all the categories this product was associated with.
	$query = "DELETE FROM ".$xoopsDB->prefix("kshop_products_categories")." WHERE product_id='$id'";
	$res=$xoopsDB->query($query);
	if(!$res) {
		echo "error: $query <br>";
	}
	

	//-- Add associations for all the categories this product belongs to.
	//-- If no categories were selected, we will make it belong to the top category.
	if (count($frm['categories']) == 0)
	{
		$frm['categories'][] = 0;
	}

	for ($i = 0; $i < count($frm['categories']); $i++)
	{
	$query = "Insert into ".$xoopsDB->prefix("kshop_products_categories")." (product_id, category_id) values ('$id','{$frm['categories'][$i]}')";
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error: $query<br>";
		}

	}


//Delete all options associated with this product
$query = "Delete from ".$xoopsDB->prefix("kshop_products_options")." where product_id=$id ";
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error: $query";
		}

//Add new options associated with this product
	$cme= count($_POST['p_options']);
	
	//Add options to this product.
	for ($i = 0; $i < $cme; $i++)
	{
		$query = "Insert into ".$xoopsDB->prefix("kshop_products_options")." (product_id, options_id) values ('$id','{$_POST['p_options'][$i]}')";
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error: $query<br>";
		}
	}
	
	//Delete brand
	$query = "Delete from ".$xoopsDB->prefix("kshop_products_brands")." where product_id=$id ";
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error: $query";
		}
	//Insert new brand
		$query = "Insert into ".$xoopsDB->prefix("kshop_products_brands")." (product_id, brand_id) values ('$id','{$_POST['brands']}')";
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error: $query<br>";
		}

	
redirect_header('products.php', 1, KS_UPDATESUCESS);
die();
}



/**
* Read all the products from the database and print them into a table.
* We will use a template to display the listings to keep this main script clean.
*/
function printList($start=1, $rows_per_page=3)
{

global $xoopsTpl;


$prods=loadallProd();
$xoopsTpl->assign('prods', $prods);

$xoopsTpl->assign('mode', 'insert');


$xoopsTpl->display('db:admin_products_list.html');
}


/**
* Read all the products from the database and print them into a table.
* We will use a template to display the listings to keep this main script clean.
*/
function printSearchResult($search)
{
	global $CFG;

	$sql = db_query("SELECT   p_id,
                              p_item_nr,
                              p_name,
                              p_style,
                              p_price,
                              p_sp_price,
                              p_show_me,
                              p_show_img,
                              p_has_special,
                              p_has_style,
                              c_name
                     FROM     ". DB_PREFIX ."products,
                              ". DB_PREFIX ."categories,
                              ". DB_PREFIX ."products_categories
                     WHERE    p_id = product_id
                     AND      category_id = c_id
                     AND      p_name LIKE '%". $search ."%'
                     ORDER BY c_id, p_item_nr
                    ");

	include("./templates/tpl.a_product_list.php");
}

xoops_cp_footer();
?>