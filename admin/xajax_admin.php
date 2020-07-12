<?php
// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

function addStatus($oid){
global $order,$xoopsTpl;

$xoopsTpl->assign('oid', $oid);
$xoopsTpl->assign('status', $order->loadStatus());


$text = $xoopsTpl->fetch('db:admin_xajax_add_status.html');

$objResponse = new xajaxResponse();
$objResponse->assign("addNewDiv","innerHTML",$text);
return $objResponse;

}

function plugType($type){
global $plugin,$xoopsTpl;

$xoopsTpl->assign('plugs', $plugin->InstalledPlugs($type));
$xoopsTpl->assign('avails', $plugin->CheckAvailPlug($type));

$text = $xoopsTpl->fetch('db:admin_xajax_plugins.html');

$objResponse = new xajaxResponse();
$objResponse->assign("pluginDiv","innerHTML",$text);
return $objResponse;
}

//Install Plugin
function Xinstaplug($type,$plugname){
global $plugin,$xoopsTpl;

$plugin->pluginInstaller($type,$plugname);

$xoopsTpl->assign('plugs', $plugin->InstalledPlugs($type));
$xoopsTpl->assign('avails', $plugin->CheckAvailPlug($type));

$text = $xoopsTpl->fetch('db:admin_xajax_plugins.html');

	$objResponse = new xajaxResponse();
	$objResponse->assign("pluginDiv","innerHTML",$text);
	return $objResponse;
}

//Uninstall plugin
function Xunplug($type,$pid){
global $plugin,$xoopsTpl;

$plugin->UninstPlugin($pid);
$xoopsTpl->assign('plugs', $plugin->InstalledPlugs($type));
$xoopsTpl->assign('avails', $plugin->CheckAvailPlug($type));

$text = $xoopsTpl->fetch('db:admin_xajax_plugins.html');

	$objResponse = new xajaxResponse();
	$objResponse->assign("pluginDiv","innerHTML",$text);
	return $objResponse;
}


?>