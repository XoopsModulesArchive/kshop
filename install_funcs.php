<?php

// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

// Uninstall plugin tables that might be left over.
function xoops_module_uninstall_kshop(&$module) {
	global $xoopsDB;

	$typeloop= array ("0" => "shipping", "1" => "payment", "2" => "order");

	for ($k=0;$k<=2;$k++){
		$type=$typeloop[$k];
		
		$dir= opendir(XOOPS_ROOT_PATH.'/modules/kshop/plugins/'.$type);
		while ($file=readdir($dir)){

			if ($file!="." && $file!=".."){
				include_once XOOPS_ROOT_PATH.'/modules/kshop/plugins/'.$type.'/'.$file."/plugin_version.php";

				for ($i=0;;$i++){
					
					if (isset($plugin['tables'][$i])){
					$tablename=$plugin['tables'][$i];
					} else {
					break;
					}

				


					if(TableExists($xoopsDB->prefix($tablename)))
					{
						$result = $xoopsDB->queryF("DROP TABLE ".$xoopsDB->prefix($tablename)." ");
						if(!$result) {
							return FALSE;
						}
					}
				}
			}
		}
	}
	return TRUE;
}





function TableExists($tablename)
{
	global $xoopsDB;
	$result=$xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");
	return($xoopsDB->getRowsNum($result) > 0);
}


?>