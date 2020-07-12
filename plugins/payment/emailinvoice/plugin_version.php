<?php
/*--------------------------

Sample Plugin

Use this as a reference for your own plugins.

//------------------------*/


//Name of Plugin
$plugin['name']= PAYEMLPVNAME;

//Version of Plugin. In order for it to work properly please keep it like: X.XX
$plugin['version']= "1.50";

//Plugin Description.
$plugin['description'] = PAYEMLPVDESC;

//Directory of plugin. This should corresponde exactly to the name of this plugin directory.
$plugin['dirname'] = "emailinvoice";

//Type of plugin; can be either payment, shipping or order total
$plugin['type'] = "payment";

//Sql file to used to create plugin tables and insert data.
$plugin['sqlfile'] = "mysql.sql";

//Tables created by sql file. This is needed so that this plugin can be properly uninstalled.
$plugin['tables'][0] = "kshop_plug_emailinvoice";

//Set the template to be used in the checkout procedure
$plugin['checkout_template'] = "main.html";

?>