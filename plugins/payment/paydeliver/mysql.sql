CREATE TABLE kshop_plug_paydeliver (
  id int(4) unsigned NOT NULL auto_increment,
  cost int(6) unsigned NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM;

INSERT INTO kshop_plug_paydeliver VALUES (1, 6);