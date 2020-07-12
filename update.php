<?php

// +--------------------------------------------------------------------------+
// |							Kshop
// | 							Module for Xoops
// |            				www.kaotik.biz
// |            				kaotik1@gmail.com
// +--------------------------------------------------------------------------+

include '../../mainfile.php';
include XOOPS_ROOT_PATH.'/header.php';

echo "starting upgrade process<br />";

$result = $xoopsDB->queryF("CREATE TABLE ".$xoopsDB->prefix("kshop_products_brands")."(
 `mid` int(10) unsigned NOT NULL auto_increment,
 `product_id` int(10) unsigned NOT NULL,
 `brand_id` int(10) unsigned NOT NULL,
  PRIMARY KEY  (mid),
  KEY product_id (product_id)
) ENGINE=MyISAM");


if (!$result) {
	echo "Create Table: kshop_products_brands - FAILED <br />";
}
else {
	echo "Create Table: kshop_products_brands - OK <br />";
}

$result = $xoopsDB->queryF("CREATE TABLE ".$xoopsDB->prefix("kshop_brands")."(
 `mid` int(10) unsigned NOT NULL auto_increment,
 `name` varchar(24) NOT NULL,
  PRIMARY KEY  (mid),
  KEY name (name)
) ENGINE=MyISAM");


if (!$result) {
	echo "Create Table: kshop_brands - FAILED <br />";
}
else {
	echo "Create Table: kshop_brands - OK <br />";
}

$result = $xoopsDB->queryF("CREATE TABLE ".$xoopsDB->prefix("kshop_text")."(
 `mid` int(10) unsigned NOT NULL auto_increment,
 `area` int(2) NOT NULL,
 `textarea` text NOT NULL,
  PRIMARY KEY  (mid),
  KEY area (area)
) ENGINE=MyISAM");


if (!$result) {
	echo "Create Table: kshop_text - FAILED <br />";
}
else {
	echo "Create Table: kshop_text - OK <br />";
}

echo "Update process is complete. Please erase this file!";

include XOOPS_ROOT_PATH.'/footer.php';

?>