<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+


include 'admin_header.php';

xoops_cp_header();

$mainTabs->setCurrent('categories', 'tabs');
$mainTabs->display();

(isset($_REQUEST['mode'])) ? $mode=$_REQUEST['mode'] : $mode='';

switch ($mode)
{
	case "add" :
	addCategory($_REQUEST['id'], 1);
	break;

	case "edit" :
	editCategory($_REQUEST['id']);
	break;

	case "del" :
	deleteCategory($_REQUEST['id']);
	printList();
	break;

	case "insert" :
	insertSubcategory($_REQUEST['id'], $_POST);
	printList();
	break;

	case "update" :
	updateCategory($_REQUEST['id'], $_POST);
	printList();
	break;

	default :
	printList();
	break;
}



/**
* Print a blank category form so we can add a new category.
*/
function addCategory($id)
{
	global $xoopsTpl;

//Preselect the 'Home' category
$scat[1]['id']=1;

$cats=buildCategoryTree(1,$scat);
$xoopsTpl->assign('cats', $cats);

$xoopsTpl->assign('mode', 'insert');

$editor=getEditor('caption', 'c_desc', '', '100%', '200px');
$xoopsTpl->assign('desc', $editor);

$xoopsTpl->display('db:admin_category.html');
	//include("./templates/tpl.a_category_form.php");
}


/**
* Print a category form so we can add a edit the selected category.
*/
function editCategory($id)
{
global $xoopsTpl;

//include ('javaimagemanage.php');

$lcat=loadCat($id);
$xoopsTpl->assign('lcat', $lcat);
$xoopsTpl->assign('name', $lcat['name']);
$xoopsTpl->assign('order', $lcat['order']);
$xoopsTpl->assign('img', $lcat['img']);

$xoopsTpl->assign('mode', 'update');
$xoopsTpl->assign('id', $id);


$scat=array();
$scat[1]['id']=$lcat['parent'];
$scat[1]['name']=$lcat['name'];

$cats=buildCategoryTree(1,$scat);
$xoopsTpl->assign('cats', $cats);

$xoopsTpl->assign('showimg', printChecked($lcat['showimg']));


$editor=getEditor('c_description', 'c_description', $lcat['desc'], '100%', '200px');
$xoopsTpl->assign('desc', $editor);

$xoopsTpl->display('db:admin_category.html');
}


/**
* Delete the category specified by $id, and move all the products under
* that category to the immediate parent. This should be wrapped inside a
* transaction, unfortunately MySQL currently does not support transactions.
*/
function deleteCategory($id)
{
	global $xoopsDB;

if ($id==1){
redirect_header('categories.php', 1, KS_NODELCAT1);
die();
}

	//-- Re-assign all the products in this category to the Home category.
	$query = "UPDATE ".$xoopsDB->prefix("kshop_products_categories")."
       				  SET   category_id = '1'
              WHERE   category_id = '$id'";
			  
		$res=$xoopsDB->queryF($query);
		if(!$res) {
			echo "error:$query <br>";
		}

//-- Re-assign all sub categories of this category to the Home category.
$query = "UPDATE ".$xoopsDB->prefix("kshop_categories")."
       				  SET   c_parent_id = '1'
              WHERE   c_parent_id = '$id'";
			  
		$res=$xoopsDB->queryF($query);
		if(!$res) {
			echo "error:$query <br>";
		}

//-- Delete this category.
	$query = "DELETE FROM ".$xoopsDB->prefix("kshop_categories")." WHERE c_id='$id'";
	$res=$xoopsDB->queryF($query);
	if(!$res) {
		echo "error: $query <br>";
	}
redirect_header('categories.php', 1, KS_DELCATSUCESS);
die();
}


/**
* Add a new subcategory under the parent $id.
* All the fields that we want are going to be in the variable $frm.
*/
function insertSubcategory($id, $frm)
{
	global $xoopsDB;
	

	(isset($frm['c_show_image'])) ? $frm['c_show_image']=1 : $frm['c_show_image']='';

	//Sanitize text
	$myts = myTextSanitizer::getInstance();
	$frm['c_desc'] = $myts->addslashes($frm['c_desc']);
	$frm['c_name'] = $myts->addslashes($frm['c_name']);


$query = "Insert into ".$xoopsDB->prefix("kshop_categories")."
					(c_parent_id, c_name, c_description, c_order, c_image, c_show_image)
	VALUES ('$frm[parent]', '$frm[c_name]', '$frm[c_desc]', '$frm[c_order]', '$frm[c_image]', '$frm[c_show_image]')";

	$res=$xoopsDB->query($query);
	if(!$res) {
		echo "error: $query";
	}

	//-- get the product id that was just created.
	$product_id = mysql_insert_id();
	
redirect_header('categories.php', 1, KS_CREATCATSUCESS);
die();
}


/**
* Update the category $id with new values.
* All the fields that we want are going to in the variable $frm.
*/
function updateCategory($id, $frm)
{
global $xoopsDB;
	

	(isset($frm['c_show_image'])) ? $frm['c_show_image']=1 : $frm['c_show_image']='';

	//Sanitize text
	$myts = myTextSanitizer::getInstance();
	//$san_c_desc = $myts->addslashes($frm['c_description']);
	$san_c_name = $myts->addslashes($frm['c_name']);
	($id==1) ? $parent=1 : $parent=$frm['parent'];

	//-- Update the categories table with the new information.
	$query = "UPDATE ".$xoopsDB->prefix("kshop_categories")." SET  c_parent_id = '".$parent."',
                      c_name = '".$san_c_name."',
                      c_description = '".$frm['c_description']."',
                      c_order = '".$frm['c_order']."',
                      c_image = '".$frm['c_image']."',
                      c_show_image = '".$frm['c_show_image']."'
              			WHERE   c_id = '$id'";
	//-------------------*/
			  
		$res=$xoopsDB->query($query);
		if(!$res) {
			echo "error: $query";
		}
}


/**
* Lists all categories.
*/
function printList()
{
global $xoopsTpl;

$cats=listCats();
$xoopsTpl->assign('cats', $cats);

$xoopsTpl->display('db:admin_category_list.html');
}

xoops_cp_footer();
?>