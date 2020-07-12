CREATE TABLE kshop_plug_shipland (
  id int(4) unsigned NOT NULL auto_increment,
  order_total int(6) unsigned NOT NULL default '0',
  discount int(3) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM;

INSERT INTO kshop_plug_shipland VALUES (1, 100, 7);