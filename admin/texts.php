<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

/*
Text id
1 - Main text on initial page
2 - Terms
3 - Privacy
4 - Returns
5 - Company Info
*/
include 'admin_header.php';
xoops_cp_header();

$mainTabs->setCurrent('text', 'tabs');
$mainTabs->display();


if (isset($_POST['mode'])){
insertText($_POST);
} elseif (isset($_GET['txt'])) {
printForm($_GET['txt']);
} else {
printForm();
}

function insertText($txt){
global $xoopsTpl,$xoopsDB;

//Sanitize text
	$myts = myTextSanitizer::getInstance();
	$txt['tid'] = $myts->addslashes($txt['tid']);
	$txt['ktext'] = $myts->addslashes($txt['ktext']);

$query = "UPDATE ".$xoopsDB->prefix("kshop_text")."
       				  SET   textarea = '".$txt['ktext']."'
              WHERE   area = '".$txt['tid']."'";
			  
		$res=$xoopsDB->queryF($query);
		if(!$res) {
			echo "error:$query <br>";
		}
redirect_header('texts.php', 1, KS_UPDTTXTSUCESS);
die();
}

function printForm($id=1){
global $xoopsTpl,$xoopsDB;

switch ($id)
{
	case "1" :
	$name='Main Page';
	break;
	
	case "2" :
	$name='Terms';
	break;
	
	case "3" :
	$name='Privacy';
	break;
	
	case "4" :
	$name='Returns';
	break;
	
	case "5" :
	$name='Company Info';
	break;
	
	default :
	$id=1;
	$name='Main Page';
	break;
}

$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_text') . " WHERE area = '$id' ");
$row = $xoopsDB->fetchArray($query);

$editor=getEditor('caption', 'ktext', $row['textarea'], '95%', '600px');
$xoopsTpl->assign('textfield', $editor);

$xoopsTpl->assign('name', $name);
$xoopsTpl->assign('tid', $id);
$xoopsTpl->assign('mode', 'insert');
$xoopsTpl->display('db:admin_textarea.html');
}


xoops_cp_footer();
?>