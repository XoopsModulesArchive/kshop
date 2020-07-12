<?php

function shop_categories (){
	global $xoopsDB, $xoopsModuleConfig,$xoopsConfig,$KmodName;
	$KmodName='kshop';
	
	$module_handler =& xoops_gethandler('module');
	//Replace 'mymodule' with the name of your module
	$module =& $module_handler->getByDirname($KmodName);
	$config_handler =& xoops_gethandler('config');
	$moduleConfig =& $config_handler->getConfigsByCat(0, $module->getVar('mid'));

//Check language file. If users language isn't present then use english
ksLangCheck($module->getVar('dirname'));

	$min_order = $moduleConfig['minorder'];
			
	$block['specialon']=$moduleConfig['groupspecial'];
if ((isset($_GET['id'])) && ($_GET['id']==999)){
$block['spclSel']=1;
}	
	$block['dirname']=$module->getVar('dirname');

//Build category tree. This sorts main categories and subcategories.
$catholder =treeMaker();
$block['tree']=$catholder;

	return $block;
}

function catLoader(){
global $xoopsDB;
$q=1;
$catTotal=array();
	$query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_categories'). " ORDER BY `c_id`");
	while ($row = $xoopsDB->fetchArray($query))
	{
	$catTotal[$q]['c_id']=$row['c_id'];
	$catTotal[$q]['c_name']=$row['c_name'];
	$catTotal[$q]['c_parent_id']=$row['c_parent_id'];
	$q++;
}
return $catTotal;
}

function treeMaker()
{
global $exclude,$catTotal,$KmodName;
$tree = "";					// Clear the directory tree
$depth = 1;					// Child level depth.
$top_level_on = 1;			// What top-level category are we on?
$exclude = array();			// Define the exclusion array
array_push($exclude, 0);	// Put a starting value in it

	$catTotal=catLoader();
	foreach ($catTotal as $row)
	{
	$goOn = 1;			// Resets variable to allow us to continue building out the tree.
	foreach($exclude as $exclu)		// Check to see if the new item has been used
	{
		if ( $exclu == $row['c_id'] )
		{
			$goOn = 0;
			break;				// Stop looking b/c we already found that it's in the exclusion list and we can't continue to process this node
		}
	}
	if ( $goOn == 1 )
	{
		$tree .= "<a href='".XOOPS_URL."/modules/".$KmodName."/index.php'>".$row['c_name'] . "</a><br>";				// Process the main tree node
		array_push($exclude, $row['c_id']);		// Add to the exclusion list
		if ($row['c_id'] < 6 )
		{ $top_level_on = $row['c_id']; }
		
		$tree .= build_child($row['c_id']);		// Start the recursive function of building the child tree
	}

	}
	return $tree;
}

function build_child($oldID)			// Recursive function to get all of the children...unlimited depth
{
	global $exclude, $depth, $xoopsDB,$catTotal,$KmodName;	// Refer to the global array defined at the top of this script
	//$child_query = $xoopsDB->query(' SELECT * FROM ' . $xoopsDB->prefix('kshop_categories'). " WHERE c_parent_id='$oldID'");
	
	$chlCat=array();
	$q=1;
	foreach ($catTotal as $cat){
	if ($cat['c_parent_id']==$oldID){
	$chlCat[$q]['c_id']=$cat['c_id'];
	$chlCat[$q]['c_parent_id']=$cat['c_parent_id'];
	$chlCat[$q]['c_name']=$cat['c_name'];
	$q++;
	}
	}

	foreach ($chlCat as $child)
	{
		if ( $child['c_id'] != $child['c_parent_id'] )
		{
			for ( $c=0;$c<$depth;$c++ )			// Indent over so that there is distinction between levels
			{ (isset($tempTree)) ? $tempTree .= "&nbsp;" : $tempTree = "&nbsp;"; }
			if (!isset($tempTree)) $tempTree = "";
			if ((isset($_GET['id'])) && ($_GET['id']==$child['c_id'])){
			$tempTree .= "-<a href='".XOOPS_URL."/modules/".$KmodName."/index.php?id=".$child['c_id']."'><span class='KSselCat'>" . $child['c_name'] . "</span></a><br>";
			} else {
			$tempTree .= "-<a href='".XOOPS_URL."/modules/".$KmodName."/index.php?id=".$child['c_id']."'>" . $child['c_name'] . "</a><br>";
			}
			$depth++;		// Incriment depth b/c we're building this child's child tree  (complicated yet???)
			$tempTree .= build_child($child['c_id']);		// Add to the temporary local tree
			$depth--;		// Decrement depth b/c we're done building the child's child tree.
			array_push($exclude, $child['c_id']);			// Add the item to the exclusion list
		}
	}
	if (!isset($tempTree)) $tempTree = "";
	return $tempTree;		// Return the entire child tree
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