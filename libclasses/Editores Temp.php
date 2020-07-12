<?php
function smartsection_getEditor($caption, $name, $value, $dhtml = true)
{
	$smartConfig =& smartsection_getModuleConfig();
	switch ($smartConfig['use_wysiwyg']) {
case 'tiny' :
	if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/tinyeditor/formtinyeditortextarea.php"))	{
				include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/tinyeditor/formtinyeditortextarea.php");
	$editor = new XoopsFormTinyeditorTextArea(array('caption'=>$caption, 'name'=>$name, 'value'=>$value, 'width'=>'650px', 'height'=>'400px'));
	} else {
	if ($dhtml) {
	$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);	
	} else {
	$editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);	
	}
			}
	break;		
		case 'koivi' :
			if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/koivi/formwysiwygtextarea.php"))	{
				include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/koivi/formwysiwygtextarea.php");
				//$editor = new XoopsFormWysiwygTextArea($caption, $name, $value, '100%', '400px');
				$editor = new XoopsFormWysiwygTextArea($caption, $name, $value, '100%', '400px', '');
			} else {
				if ($dhtml) {
					$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);	
				} else {
					$editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);	
				}
			}
		break;
		
		
		
		default :
			if ($dhtml) {
				$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 20, 60);	
			} else {
				$editor = new XoopsFormTextArea($caption, $name, $value, 7, 60);	
			}
		
		break;
		
	}

//include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/tinyeditor/formtinyeditortextarea.php");
//$editor = new XoopsFormTinyeditorTextArea(array('caption'=>$caption,'name'=>$name, 'value'=>$value, 'width'=>'650px', 'height'=>'400px'));

	return $editor;
}


/**
 * Retreive an editor according to the module's option "form_options"
 */
function &news_getWysiwygForm($caption, $name, $value = "", $width = '100%', $height = '400px', $supplemental='')
{
	$editor = false;
	$x22=false;
	$xv=str_replace('XOOPS ','',XOOPS_VERSION);
	if(substr($xv,2,1)=='2') {
		$x22=true;
	}
	$editor_configs=array();
	$editor_configs["name"] =$name;
	$editor_configs["value"] = $value;
	$editor_configs["rows"] = 35;
	$editor_configs["cols"] = 60;
	$editor_configs["width"] = "100%";
	$editor_configs["height"] = "400px";


	switch(strtolower(getmoduleoption('form_options'))){
	case "spaw":
		if(!$x22) {
			if (is_readable(XOOPS_ROOT_PATH . "/class/spaw/formspaw.php"))	{
				include_once(XOOPS_ROOT_PATH . "/class/spaw/formspaw.php");
				$editor = new XoopsFormSpaw($caption, $name, $value);
			}
		} else {
			$editor = new XoopsFormEditor($caption, "spaw", $editor_configs);
		}
		break;

	case "fck":
		if(!$x22) {
			if ( is_readable(XOOPS_ROOT_PATH . "/class/fckeditor/formfckeditor.php"))	{
				include_once(XOOPS_ROOT_PATH . "/class/fckeditor/formfckeditor.php");
				$editor = new XoopsFormFckeditor($caption, $name, $value);
			}
		} else {
			$editor = new XoopsFormEditor($caption, "fckeditor", $editor_configs);
		}
		break;

	case "htmlarea":
		if(!$x22) {
			if ( is_readable(XOOPS_ROOT_PATH . "/class/htmlarea/formhtmlarea.php"))	{
				include_once(XOOPS_ROOT_PATH . "/class/htmlarea/formhtmlarea.php");
				$editor = new XoopsFormHtmlarea($caption, $name, $value);
			}
		} else {
			$editor = new XoopsFormEditor($caption, "htmlarea", $editor_configs);
		}
		break;

	case "dhtml":
		if(!$x22) {
			$editor = new XoopsFormDhtmlTextArea($caption, $name, $value, 10, 50, $supplemental);
		} else {
			$editor = new XoopsFormEditor($caption, "dhtmltextarea", $editor_configs);
		}
		break;

	case "textarea":
		$editor = new XoopsFormTextArea($caption, $name, $value);
		break;

	case "koivi":
		if(!$x22) {
			if ( is_readable(XOOPS_ROOT_PATH . "/class/wysiwyg/formwysiwygtextarea.php"))	{
				include_once(XOOPS_ROOT_PATH . "/class/wysiwyg/formwysiwygtextarea.php");
				$editor = new XoopsFormWysiwygTextArea($caption, $name, $value, '100%', '400px', '');
			}
		} else {
			$editor = new XoopsFormEditor($caption, "koivi", $editor_configs);
		}
		break;
		
		case "tinyeditor":
if ( is_readable(XOOPS_ROOT_PATH . "/class/xoopseditor/tinyeditor/formtinyeditortextarea.php")) {
include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/tinyeditor/formtinyeditortextarea.php");
$editor = new XoopsFormTinyeditorTextArea(array('caption'=>$caption,'name'=>$name, 'value'=>$value, 'width'=>'100%', 'height'=>'400px'));
}
break;
	}
	
	
	return $editor;
}

// lenny code
if ($lenny=="tiny"){
include_once(XOOPS_ROOT_PATH . "/class/xoopseditor/tinyeditor/formtinyeditortextarea.php");
$editor = new XoopsFormTinyeditorTextArea(array('caption'=>_C_CONTENT,'name'=>'message', 'value'=>$message, 'width'=>'100%', 'height'=>'400px'));
$form->addElement($editor,false);
}


if ($lenny=="fck"){
	if ($xoopsModuleConfig['cont_wysiwyg'] == '1') {
		$fckeditor_root = XOOPS_ROOT_PATH.'/modules/content/admin/fckeditor/';
		include XOOPS_ROOT_PATH.'/modules/content/admin/fckeditor/fckeditor.php';
		ob_start();
		$oFCKeditor = new FCKeditor('message') ;
		$oFCKeditor->BasePath	= XOOPS_URL."/modules/content/admin/fckeditor/" ;
		$oFCKeditor->Value		= $message ;
		$oFCKeditor->Height		= 500 ;
		$oFCKeditor->Create() ;
		$form->addElement(new XoopsFormLabel(_C_CONTENT, ob_get_contents()));
		ob_end_clean();
	} else {
		$t_area = new XoopsFormDhtmlTextArea(_C_CONTENT, 'message', '', 37, 35);
		$form->addElement($t_area);
	}
	}

?>
