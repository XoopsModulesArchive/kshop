<?php
/*--------------------------

Sample Plugin

Use this as a reference for your own plugins.

//------------------------*/


//Name of Plugin
$plugin['name']= PAYBNKPVNAME;

//Version of Plugin. In order for it to work properly please keep it like: X.XX
$plugin['version']= "1.50";

//Plugin Description. Enter a brief descrition so that people can have an ideia of what this plugin does.
$plugin['description'] = PAYBNKPVDESC;

//Directory of plugin. This should corresponde exactly to the name of this plugin directory.
$plugin['dirname'] = "banktransfer";

//Type of plugin; can be either payment, shipping or order
$plugin['type'] = "payment";

//Sql file to used to create plugin tables and insert data.
$plugin['sqlfile'] = "mysql.sql";

//Tables created by sql file. This is needed so that this plugin can be properly uninstalled. You should also use "kshop_plug_" before any table you create. Kshop, on uninstall will check for any kshop_plug any also uninstall it, so if you don't name it that, when someone uninstalls kshop, that table will be left behind. So next time when you try to install it will return an error.
$plugin['tables'][0] = "kshop_plug_banktransfer";


//Set the template to be used in the checkout procedure
$plugin['checkout_template'] = "main.html";

?>