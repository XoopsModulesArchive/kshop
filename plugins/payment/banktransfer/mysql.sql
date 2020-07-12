CREATE TABLE kshop_plug_banktransfer (
  id int(4) unsigned NOT NULL auto_increment,
  percentage float(2,2) NOT NULL,
  add_amount float(3,3) NOT NULL,
  acc_holder varchar(20) NOT NULL,
  bank_name varchar(20) NOT NULL,
  bank_branch varchar(20) NOT NULL,
  acc_num varchar(30) NOT NULL,
  inter_num varchar(30) NOT NULL,
  PRIMARY KEY  (id)
) ENGINE=MyISAM;

INSERT INTO kshop_plug_banktransfer VALUES (1, 1.25, .50,'','','','','');