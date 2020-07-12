<?php

function shop_categories (){
	global $xoopsDB, $xoopsModuleConfig,$xoopsConfig;
	
	$module_handler =& xoops_gethandler('module');
	//Replace 'mymodule' with the name of your module
	$module =& $module_handler->getByDirname('kshop');
	$config_handler =& xoops_gethandler('config');
	$moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

//Check language file. If users language isn't present then use english
ksLangCheck($module->getVar('dirname'));

	$min_order = $moduleConfig['minorder'];
			
	$block['specialon']=$moduleConfig['groupspecial'];
	$block['dirname']=$module->getVar('dirname');

//Build category tree. This sorts main categories and subcategories.
require_once XOOPS_ROOT_PATH . '/modules/'.$module->getVar('dirname').'/libclasses/lib.shop.php';
$catholder =buildCategoryTree();
$block['shop_cats']=$catholder;

	return $block;
}

function ksLangCheck($dir){
	global $xoopsConfig;

$langfile= XOOPS_ROOT_PATH . '/modules/'.$dir.'/language/'.$xoopsConfig['language'].'/'.'main.php';

	if (file_exists($langfile))
	{
	include_once $langfile;
	} else {
		$langfile= XOOPS_ROOT_PATH . '/modules/'.$dir.'/language/english/'.'main.php';
	include_once $langfile;
	}
}

?>