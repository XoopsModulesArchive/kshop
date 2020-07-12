<?php
/*--------------------------

Sample Plugin

Use this as a reference for your own plugins.

//------------------------*/


//Name of Plugin
$plugin['name']= DISABSOLPVNAME;

//Version of Plugin. In order for it to work properly please keep it like: X.XX
$plugin['version']= "1.50";

//Plugin Description. Enter a brief descrition so that people can have an ideia of what this plugin does.
$plugin['description'] = DISABSOLPVDESC;

//Directory of plugin. This should corresponde exactly to the name of thus plugin directory.
$plugin['dirname'] = "discountabsolut";

//Type of plugin; can be either payment, shipping or order total
$plugin['type'] = "order";

//Sql file to used to create plugin tables and insert data.
$plugin['sqlfile'] = "mysql.sql";

//Tables created by sql file. This is needed so that this plugin can be properly uninstalled.
$plugin['tables'][0] = "kshop_plug_discountabsolut";


?>