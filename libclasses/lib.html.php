<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

	function checkChecked(&$var, $set_checked = 1, $unset_checked = 0)
	{
		if (empty($var))
		{
			$var = $unset_checked;
		}
		else
		{
			$var = $set_checked;
		}
	}


function textBox($area){
	global $xoopsDB;

	//Test to see if valid number was used for $area
	$alw=array(1,2,3,4,5);
	$ar=1;
	foreach ($alw as $al){
		if ($al==$area){
			$ar=$area;
			break;
		}
	}

	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_text')." WHERE area='$ar' ");
	$row = $xoopsDB->fetchArray($query);
	$text=$row['textarea'];
	return $text;
}

function getEditor($caption, $name, $value, $width, $height)
{
	global $xoopsModuleConfig;

	$edit=$xoopsModuleConfig['editor'];

	include_once(XOOPS_ROOT_PATH . "/class/xoopsform/formelement.php");

	switch ($xoopsModuleConfig['editor']) {

		case 'koivi' :
		if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/koivi/formwysiwygtextarea.php"))
		{
			include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/koivi/formwysiwygtextarea.php");
			include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/koivi/language/english.php");
			$edit = new XoopsFormWysiwygTextArea($caption, $name, $value, '100%', '400px', '');

			$edit->setValue($value);
			$edit->_name=$name;
			$edit->width=$width;
			$edit->height=$height;

			$res=$edit->render();

		} else {
			$res = '<textarea name="'.$name.'">'.$value.'</textarea>';
		}
		break;

		case 'textarea' :
		$res = '<textarea name="'.$name.'">'.$value.'</textarea>';
		break;

		case 'tiny' :

		break;
	}
	return $res;
}


//Create thumbnails
function createThumb($image){
	$p_thumbname=substr(strrchr($image, '/'), 1);
	$p_tnprefix='thumb_'.$p_thumbname;
	$p_thumb=str_replace($p_thumbname,$p_thumbname,$image);
	$p_thumb=str_replace($p_thumbname,$p_tnprefix,$image);
	$p_thumb=str_replace($p_thumbname,'thumbs/'.$p_tnprefix,$image);
	return $p_thumb;
}




function checkLang($plugtype, $dirname){
	global $xoopsConfig;
	$langfile= XOOPS_ROOT_PATH."/modules/kshop/plugins/$plugtype/$dirname/language/".$xoopsConfig['language'].".php";
	if (!file_exists($langfile))
	{
		$langfile= XOOPS_ROOT_PATH."/modules/kshop/plugins/$plugtype/$dirname/language/english.php";
		return $langfile;
	} else {
		$langfile= XOOPS_ROOT_PATH."/modules/kshop/plugins/$plugtype/$dirname/language/".$xoopsConfig['language'].".php";
		return $langfile;
	}
}



function checkSpecial($special){
	$output="";
	if ($special!="0.00"){
		$output=$special;
		return $output;
	}
	return $output;
}

/**
* Prints the word "checked" if a variable is true, otherwise prints nothing.
* Used for printing the word "checked" in a checkbox form input.
*/
function printChecked($chk)
{
	if ($chk==1){
		$out='checked';
	}else{
		$out='';
	}
	return $out;
}

?>