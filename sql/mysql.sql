CREATE TABLE kshop_products_brands (
  mid int(10) unsigned NOT NULL auto_increment,
  product_id int(10) unsigned NOT NULL,
  brand_id int(10) unsigned NOT NULL,
  PRIMARY KEY  (mid),
  KEY product_id (product_id)
) ENGINE=MyISAM;


CREATE TABLE kshop_brands (
  mid int(10) unsigned NOT NULL auto_increment,
  name varchar(24) NOT NULL,
  PRIMARY KEY  (mid),
  KEY name (name)
) ENGINE=MyISAM;
INSERT INTO `kshop_brands` (`mid`, `name`) VALUES (12, 'Nike'),
(11, 'Sony');


CREATE TABLE kshop_categories (
  c_id int(10) unsigned NOT NULL auto_increment,
  c_parent_id int(10) unsigned NOT NULL default '1',
  c_name varchar(24) NOT NULL default '',
  c_description varchar(255) NOT NULL default 'n/a',
  c_order int(11) NOT NULL,
  c_image varchar(30) NOT NULL default '',
  c_show_image tinyint(3) NOT NULL,
  PRIMARY KEY  (c_id),
  KEY c_name (c_name),
  KEY c_parent_id (c_parent_id)
) ENGINE=MyISAM;

CREATE TABLE kshop_text (
  mid int(10) unsigned NOT NULL auto_increment,
  area int(2) NOT NULL,
  textarea text NOT NULL,
  PRIMARY KEY  (mid),
  KEY area (area)
) ENGINE=MyISAM;

INSERT INTO kshop_text (mid, area, textarea) VALUES (1, 1, '<div align="center"><font size="+4"><strong><font color="#009999">Welcome to Kshop!</font></strong></font></div><div align="center"><font color="#ff6600">Home of the freshest grocery store on the internet!</font><br /><font size="+1">Buy our fresh produce, we garante freshness, plus get a discount on volume shopping.</font></div><br />');
INSERT INTO kshop_text (mid, area, textarea) VALUES (2, 2, '<div align="center"><font size="+2"><strong>Terms</strong></font></div><div align="center">Here you can find our terms.<br /></div>To shop on our site please respect our other clients. ');
INSERT INTO kshop_text (mid, area, textarea) VALUES (3, 3, '<div align="center"><font size="+2"><strong>Privacy</strong></font></div><br /><strong>Your data is important to use. As such we willl not divulge or share any of your personal data with anyone outside our company.</strong>');
INSERT INTO kshop_text (mid, area, textarea) VALUES (4, 4, '<div align="center"><font size="+2"><strong>Returns</strong></font></div><br /><font size="+1">Y</font>ou may return any item as long as the following conditions are met:<br /><strong>1</strong>- It must have the box intact<br /><strong>2</strong>- Product cannot be damaged.');
INSERT INTO kshop_text (mid, area, textarea) VALUES (5, 5, '<div align="center"><font size="+2"><strong>Our company</strong></font></div><br /><strong>Our company has a long history of satisfied costumers. For us the client always comes first!</strong>');
   


INSERT INTO `kshop_categories` (`c_id`, `c_parent_id`, `c_name`, `c_description`, `c_order`, `c_image`, `c_show_image`) VALUES (1, 1, 'Home', 'This entry must not be removed!', 1, '', 0),
(9, 1, 'Fruits', ' <!-- .style2 { 	color: #0000FF; 	font-size: 18px; } -->  <div class="style2">   <p> </p>   <p align="right"> </p><p align="right"> </p><p align="right"><font size="5" face="verdana,geneva" color="#339933"><strong>Freshest fruit on the Market!</strong></f', 10, '/categories/fruits.jpg', 1),
(10, 1, 'Vegetables', '<div><font size="5" face="book antiqua,palatino" color="#66cc99"><strong>Hand selected from the ground, only the best vegetables get delivered to your door!</strong></font></div>', 20, '/categories/vegetables.jpg', 1);
        
CREATE TABLE kshop_products (
  p_id int(10) unsigned NOT NULL auto_increment,
  p_item_nr varchar(12) NOT NULL default '',
  p_name varchar(36) NOT NULL default '',
  p_desc varchar(255) NOT NULL default '',
  p_desc_long text NOT NULL,
  p_style varchar(20) default NULL,
  p_thumb varchar(128) default NULL,
  p_image varchar(128) default NULL,
  p_price float(15,2) NOT NULL default '0.00',
  p_sp_price float(15,2) NOT NULL default '0.00',
  p_tax float(4,2) NOT NULL default '16.00',
  p_show_me tinyint(4) NOT NULL default '1',
  p_show_img tinyint(4) NOT NULL default '1',
  p_has_special tinyint(4) NOT NULL default '0',
  p_has_style tinyint(4) NOT NULL,
  p_options varchar(60) NOT NULL default '',
  p_modified timestamp(14) NOT NULL,
  p_added timestamp(14) NOT NULL,
  PRIMARY KEY  (p_id),
  KEY p_item_nr (p_item_nr),
  KEY p_name (p_name),
  KEY p_show_me (p_show_me),
  KEY p_has_style (p_has_style)
) ENGINE=MyISAM;

INSERT INTO kshop_products (p_id, p_item_nr, p_name, p_desc, p_desc_long, p_style, p_thumb, p_image, p_price, p_sp_price, p_tax, p_show_me, p_show_img, p_has_special, p_has_style, p_options, p_modified, p_added) VALUES (4, 'A01', 'Apples', 'Apples are a great source of vitamins. As the old saying goes: an apple a day keeps the doctor away!', 'Are apples are freshly picked from the tree and are of the highest quality. Buy some and see for yourself! Price is by the dozen.', '', '/fruits/thumbs/thumb_apples.jpg', '/fruits/apples.jpg', 12.00, 0.00, 0.00, 1, 1, 0, 1, '', '2006-03-03 20:04:54', '0000-00-00 00:00:00');
INSERT INTO kshop_products (p_id, p_item_nr, p_name, p_desc, p_desc_long, p_style, p_thumb, p_image, p_price, p_sp_price, p_tax, p_show_me, p_show_img, p_has_special, p_has_style, p_options, p_modified, p_added) VALUES (5, 'A02', 'Oranges', 'Oranges are still the best source for vitamin C.', 'All balanced diets should include oranges, not only for their high nutritional value, but also there great taste. Try one!', '', '/fruits/thumbs/thumb_oranges.jpg', '/fruits/oranges.jpg', 9.00, 0.00, 0.00, 1, 1, 0, 0, '', '2006-03-03 20:05:05', '0000-00-00 00:00:00');
INSERT INTO kshop_products (p_id, p_item_nr, p_name, p_desc, p_desc_long, p_style, p_thumb, p_image, p_price, p_sp_price, p_tax, p_show_me, p_show_img, p_has_special, p_has_style, p_options, p_modified, p_added) VALUES (6, 'A03', 'Lemons', 'Great for lemonade and for the lemon lover', 'Also a great source of vitamin C', '', '/fruits/thumbs/thumb_lemon.jpg', '/fruits/lemon.jpg', 14.00, 0.00, 0.00, 1, 1, 0, 0, '', '2006-03-03 20:05:12', '0000-00-00 00:00:00');
INSERT INTO kshop_products (p_id, p_item_nr, p_name, p_desc, p_desc_long, p_style, p_thumb, p_image, p_price, p_sp_price, p_tax, p_show_me, p_show_img, p_has_special, p_has_style, p_options, p_modified, p_added) VALUES (7, 'A17', 'Strawberries', 'A main component in some of the best deserts ever made.', 'Our strawberries and picked fresh and delivered still with some ground attached so you can taste the freshness. Price is by the dozen.', '', '/fruits/thumbs/thumb_Strawberrys.jpg', '/fruits/Strawberrys.jpg', 18.00, 0.00, 0.00, 1, 1, 0, 0, '', '2006-03-03 20:05:17', '0000-00-00 00:00:00');
           

CREATE TABLE kshop_products_categories (
  product_id int(10) unsigned NOT NULL,
  category_id int(10) unsigned NOT NULL,
  PRIMARY KEY  (product_id,category_id)
) ENGINE=MyISAM;

INSERT INTO kshop_products_categories (product_id, category_id) VALUES (4, 9);
INSERT INTO kshop_products_categories (product_id, category_id) VALUES (5, 9);
INSERT INTO kshop_products_categories (product_id, category_id) VALUES (6, 9);
INSERT INTO kshop_products_categories (product_id, category_id) VALUES (7, 9);


CREATE TABLE kshop_options (
  id int(10) unsigned NOT NULL auto_increment,
  options varchar(20) NOT NULL default '0',
  PRIMARY KEY  (id)
) ENGINE=MyISAM;

INSERT INTO kshop_options (id, options) VALUES (5, 'Braeburn');
INSERT INTO kshop_options (id, options) VALUES (4, 'Golden Delicious');
INSERT INTO kshop_options (id, options) VALUES (6, 'Gala');

CREATE TABLE kshop_products_options (
  product_id int(10) unsigned NOT NULL,
  options_id int(10) unsigned NOT NULL,
  PRIMARY KEY  (product_id,options_id)
) ENGINE=MyISAM;

INSERT INTO kshop_products_options (product_id, options_id) VALUES (4, 4);
INSERT INTO kshop_products_options (product_id, options_id) VALUES (4, 5);
INSERT INTO kshop_products_options (product_id, options_id) VALUES (4, 6);


CREATE TABLE kshop_plugins (
  id int(5) unsigned NOT NULL auto_increment,
  dir varchar(20) NOT NULL,
  type varchar(10) NOT NULL,
  PRIMARY KEY  (id),
  KEY type (type)
) ENGINE=MyISAM;

CREATE TABLE kshop_users (
  id int(5) unsigned NOT NULL auto_increment,
  xid int(5) NOT NULL,
  firstname varchar(30) NOT NULL,
  lastname varchar(30) NOT NULL,
  company varchar(40) NOT NULL,
  address varchar(50) NOT NULL,
  zipcode varchar(30) NOT NULL,
  city varchar(30) NOT NULL,
  country varchar(25) NOT NULL,
  tel int(15) NOT NULL,
  fax int(15) NOT NULL,
  email varchar(50) NOT NULL,
  PRIMARY KEY  (id),
  KEY xid (xid)
) ENGINE=MyISAM;

CREATE TABLE `kshop_orders` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `xuser` int(5) NOT NULL,
  `date` timestamp NOT NULL,
  `comment` text NOT NULL,
  `paymethod` varchar(40) NOT NULL,
  `shipmethod` varchar(40) NOT NULL,
  `prodtotal` varchar(20) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `xid` (`xuser`)
) ENGINE=MyISAM;

INSERT INTO `kshop_orders` (`id`, `xuser`, `date`, `comment`, `paymethod`, `shipmethod`, `prodtotal`) VALUES (1, 1, '2006-05-31 17:13:08', '', 'banktransfer', 'airexpress', '78.4');

CREATE TABLE `kshop_status` (
  `sid` int(5) unsigned NOT NULL auto_increment,
  `status` varchar(20) NOT NULL,
  `deff` tinyint(4) NOT NULL,
  PRIMARY KEY  (`sid`),
  KEY `status` (`status`)
) ENGINE=MyISAM;


INSERT INTO `kshop_status` (`sid`, `status`, `deff`) VALUES (1, 'pending', 1);
INSERT INTO `kshop_status` (`sid`, `status`) VALUES (2, 'sent');



CREATE TABLE `kshop_orders_status` (
  `sid` int(5) unsigned NOT NULL auto_increment,
  `orderid` int(5) NOT NULL,
  `status` varchar(20) NOT NULL default '',
  `date` timestamp NOT NULL,
  `comment` text NOT NULL default '',
  PRIMARY KEY  (`sid`),
  KEY `orderid` (`orderid`)
) ENGINE=MyISAM;

INSERT INTO `kshop_orders_status` VALUES (1, 1, 'pending', '2006-05-31 17:13:08', '');


CREATE TABLE `kshop_orders_users` (
  `id` int(5) unsigned NOT NULL auto_increment,
  `orderid` int(5) NOT NULL,
  `firstname` varchar(30) NOT NULL default '',
  `lastname` varchar(30) NOT NULL default '',
  `company` varchar(40) NOT NULL default '',
  `address` varchar(50) NOT NULL default '',
  `zipcode` varchar(30) NOT NULL default '',
  `city` varchar(30) NOT NULL default '',
  `country` varchar(25) NOT NULL default '',
  `tel` int(15) NOT NULL,
  `fax` int(15) NOT NULL,
  `email` varchar(20) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `orderid` (`orderid`)
) ENGINE=MyISAM;

INSERT INTO `kshop_orders_users` (`id`, `orderid`, `firstname`, `lastname`, `company`, `address`, `zipcode`, `city`, `country`, `tel`, `fax`, `email`) VALUES (1, 1, 'John', 'Doe', 'Lost in Space', '32 backface of the Moon', '32000', 'Solar System', 'Monte Carlo', 56565656, 2147483647, 'lost@inspace.backfac');
  

CREATE TABLE kshop_orders_products (
  id int(5) unsigned NOT NULL auto_increment,
  order_id int(5) NOT NULL,
  qty int(5) NOT NULL,
  p_num varchar(30) NOT NULL default '',
  p_name varchar(50) NOT NULL default '',
  p_price varchar(20) NOT NULL default '',
  PRIMARY KEY  (id),
  KEY order_id (order_id),
  KEY p_name (p_name)
) ENGINE=MyISAM;

INSERT INTO `kshop_orders_products` (`id`, `order_id`, `qty`, `p_num`, `p_name`, `p_price`) VALUES (1, 1, 3, 'A01', 'Apples  Option: Braeburn', '12.00');
INSERT INTO `kshop_orders_products` (`id`, `order_id`, `qty`, `p_num`, `p_name`, `p_price`) VALUES (2, 1, 2, 'A02', 'Oranges', '9.00');
INSERT INTO `kshop_orders_products` (`id`, `order_id`, `qty`, `p_num`, `p_name`, `p_price`) VALUES (3, 1, 1, 'A17', 'Strawberries', '18.00');
        

CREATE TABLE `kshop_country` (
  `cid` int(11) NOT NULL auto_increment,
  `name` varchar(200) NOT NULL default '',
  PRIMARY KEY  (`cid`)
) ENGINE=MyISAM;

INSERT INTO `kshop_country` VALUES (1, 'United States');
INSERT INTO `kshop_country` VALUES (2, 'Canada');
INSERT INTO `kshop_country` VALUES (3, 'Mexico');
INSERT INTO `kshop_country` VALUES (4, 'Afghanistan');
INSERT INTO `kshop_country` VALUES (5, 'Albania');
INSERT INTO `kshop_country` VALUES (6, 'Algeria');
INSERT INTO `kshop_country` VALUES (7, 'Andorra');
INSERT INTO `kshop_country` VALUES (8, 'Angola');
INSERT INTO `kshop_country` VALUES (9, 'Anguilla');
INSERT INTO `kshop_country` VALUES (10, 'Antarctica');
INSERT INTO `kshop_country` VALUES (11, 'Antigua and Barbuda');
INSERT INTO `kshop_country` VALUES (12, 'Argentina');
INSERT INTO `kshop_country` VALUES (13, 'Armenia');
INSERT INTO `kshop_country` VALUES (14, 'Aruba');
INSERT INTO `kshop_country` VALUES (15, 'Ascension Island');
INSERT INTO `kshop_country` VALUES (16, 'Australia');
INSERT INTO `kshop_country` VALUES (17, 'Austria');
INSERT INTO `kshop_country` VALUES (18, 'Azerbaijan');
INSERT INTO `kshop_country` VALUES (19, 'Bahamas');
INSERT INTO `kshop_country` VALUES (20, 'Bahrain');
INSERT INTO `kshop_country` VALUES (21, 'Bangladesh');
INSERT INTO `kshop_country` VALUES (22, 'Barbados');
INSERT INTO `kshop_country` VALUES (23, 'Belarus');
INSERT INTO `kshop_country` VALUES (24, 'Belgium');
INSERT INTO `kshop_country` VALUES (25, 'Belize');
INSERT INTO `kshop_country` VALUES (26, 'Benin');
INSERT INTO `kshop_country` VALUES (27, 'Bermuda');
INSERT INTO `kshop_country` VALUES (28, 'Bhutan');
INSERT INTO `kshop_country` VALUES (29, 'Bolivia');
INSERT INTO `kshop_country` VALUES (30, 'Bophuthatswana');
INSERT INTO `kshop_country` VALUES (31, 'Bosnia-Herzegovina');
INSERT INTO `kshop_country` VALUES (32, 'Botswana');
INSERT INTO `kshop_country` VALUES (33, 'Bouvet Island');
INSERT INTO `kshop_country` VALUES (34, 'Brazil');
INSERT INTO `kshop_country` VALUES (35, 'British Indian Ocean');
INSERT INTO `kshop_country` VALUES (36, 'British Virgin Islands');
INSERT INTO `kshop_country` VALUES (37, 'Brunei Darussalam');
INSERT INTO `kshop_country` VALUES (38, 'Bulgaria');
INSERT INTO `kshop_country` VALUES (39, 'Burkina Faso');
INSERT INTO `kshop_country` VALUES (40, 'Burundi');
INSERT INTO `kshop_country` VALUES (41, 'Cambodia');
INSERT INTO `kshop_country` VALUES (42, 'Cameroon');
INSERT INTO `kshop_country` VALUES (44, 'Cape Verde Island');
INSERT INTO `kshop_country` VALUES (45, 'Cayman Islands');
INSERT INTO `kshop_country` VALUES (46, 'Central Africa');
INSERT INTO `kshop_country` VALUES (47, 'Chad');
INSERT INTO `kshop_country` VALUES (48, 'Channel Islands');
INSERT INTO `kshop_country` VALUES (49, 'Chile');
INSERT INTO `kshop_country` VALUES (50, 'China, Peoples Republic');
INSERT INTO `kshop_country` VALUES (51, 'Christmas Island');
INSERT INTO `kshop_country` VALUES (52, 'Cocos (Keeling) Islands');
INSERT INTO `kshop_country` VALUES (53, 'Colombia');
INSERT INTO `kshop_country` VALUES (54, 'Comoros Islands');
INSERT INTO `kshop_country` VALUES (55, 'Congo');
INSERT INTO `kshop_country` VALUES (56, 'Cook Islands');
INSERT INTO `kshop_country` VALUES (57, 'Costa Rica');
INSERT INTO `kshop_country` VALUES (58, 'Croatia');
INSERT INTO `kshop_country` VALUES (59, 'Cuba');
INSERT INTO `kshop_country` VALUES (60, 'Cyprus');
INSERT INTO `kshop_country` VALUES (61, 'Czech Republic');
INSERT INTO `kshop_country` VALUES (62, 'Denmark');
INSERT INTO `kshop_country` VALUES (63, 'Djibouti');
INSERT INTO `kshop_country` VALUES (64, 'Dominica');
INSERT INTO `kshop_country` VALUES (65, 'Dominican Republic');
INSERT INTO `kshop_country` VALUES (66, 'Easter Island');
INSERT INTO `kshop_country` VALUES (67, 'Ecuador');
INSERT INTO `kshop_country` VALUES (68, 'Egypt');
INSERT INTO `kshop_country` VALUES (69, 'El Salvador');
INSERT INTO `kshop_country` VALUES (70, 'England');
INSERT INTO `kshop_country` VALUES (71, 'Equatorial Guinea');
INSERT INTO `kshop_country` VALUES (72, 'Estonia');
INSERT INTO `kshop_country` VALUES (73, 'Ethiopia');
INSERT INTO `kshop_country` VALUES (74, 'Falkland Islands');
INSERT INTO `kshop_country` VALUES (75, 'Faeroe Islands');
INSERT INTO `kshop_country` VALUES (76, 'Fiji');
INSERT INTO `kshop_country` VALUES (77, 'Finland');
INSERT INTO `kshop_country` VALUES (78, 'France');
INSERT INTO `kshop_country` VALUES (79, 'French Guyana');
INSERT INTO `kshop_country` VALUES (80, 'French Polynesia');
INSERT INTO `kshop_country` VALUES (81, 'Gabon');
INSERT INTO `kshop_country` VALUES (82, 'Gambia');
INSERT INTO `kshop_country` VALUES (83, 'Georgia Republic');
INSERT INTO `kshop_country` VALUES (84, 'Germany');
INSERT INTO `kshop_country` VALUES (85, 'Gibraltar');
INSERT INTO `kshop_country` VALUES (86, 'Greece');
INSERT INTO `kshop_country` VALUES (87, 'Greenland');
INSERT INTO `kshop_country` VALUES (88, 'Grenada');
INSERT INTO `kshop_country` VALUES (89, 'Guadeloupe (French)');
INSERT INTO `kshop_country` VALUES (90, 'Guatemala');
INSERT INTO `kshop_country` VALUES (91, 'Guernsey Island');
INSERT INTO `kshop_country` VALUES (92, 'Guinea Bissau');
INSERT INTO `kshop_country` VALUES (93, 'Guinea');
INSERT INTO `kshop_country` VALUES (94, 'Guyana');
INSERT INTO `kshop_country` VALUES (95, 'Haiti');
INSERT INTO `kshop_country` VALUES (96, 'Heard and McDonald Isls');
INSERT INTO `kshop_country` VALUES (97, 'Honduras');
INSERT INTO `kshop_country` VALUES (98, 'Hong Kong');
INSERT INTO `kshop_country` VALUES (99, 'Hungary');
INSERT INTO `kshop_country` VALUES (100, 'Iceland');
INSERT INTO `kshop_country` VALUES (101, 'India');
INSERT INTO `kshop_country` VALUES (102, 'Iran');
INSERT INTO `kshop_country` VALUES (103, 'Iraq');
INSERT INTO `kshop_country` VALUES (104, 'Ireland');
INSERT INTO `kshop_country` VALUES (105, 'Isle of Man');
INSERT INTO `kshop_country` VALUES (106, 'Israel');
INSERT INTO `kshop_country` VALUES (107, 'Italy');
INSERT INTO `kshop_country` VALUES (108, 'Ivory Coast');
INSERT INTO `kshop_country` VALUES (109, 'Jamaica');
INSERT INTO `kshop_country` VALUES (110, 'Japan');
INSERT INTO `kshop_country` VALUES (111, 'Jersey Island');
INSERT INTO `kshop_country` VALUES (112, 'Jordan');
INSERT INTO `kshop_country` VALUES (113, 'Kazakhstan');
INSERT INTO `kshop_country` VALUES (114, 'Kenya');
INSERT INTO `kshop_country` VALUES (115, 'Kiribati');
INSERT INTO `kshop_country` VALUES (116, 'Kuwait');
INSERT INTO `kshop_country` VALUES (117, 'Laos');
INSERT INTO `kshop_country` VALUES (118, 'Latvia');
INSERT INTO `kshop_country` VALUES (119, 'Lebanon');
INSERT INTO `kshop_country` VALUES (120, 'Lesotho');
INSERT INTO `kshop_country` VALUES (121, 'Liberia');
INSERT INTO `kshop_country` VALUES (122, 'Libya');
INSERT INTO `kshop_country` VALUES (123, 'Liechtenstein');
INSERT INTO `kshop_country` VALUES (124, 'Lithuania');
INSERT INTO `kshop_country` VALUES (125, 'Luxembourg');
INSERT INTO `kshop_country` VALUES (126, 'Macao');
INSERT INTO `kshop_country` VALUES (127, 'Macedonia');
INSERT INTO `kshop_country` VALUES (128, 'Madagascar');
INSERT INTO `kshop_country` VALUES (129, 'Malawi');
INSERT INTO `kshop_country` VALUES (130, 'Malaysia');
INSERT INTO `kshop_country` VALUES (131, 'Maldives');
INSERT INTO `kshop_country` VALUES (132, 'Mali');
INSERT INTO `kshop_country` VALUES (133, 'Malta');
INSERT INTO `kshop_country` VALUES (134, 'Martinique (French)');
INSERT INTO `kshop_country` VALUES (135, 'Mauritania');
INSERT INTO `kshop_country` VALUES (136, 'Mauritius');
INSERT INTO `kshop_country` VALUES (137, 'Mayotte');
INSERT INTO `kshop_country` VALUES (139, 'Micronesia');
INSERT INTO `kshop_country` VALUES (140, 'Moldavia');
INSERT INTO `kshop_country` VALUES (141, 'Monaco');
INSERT INTO `kshop_country` VALUES (142, 'Mongolia');
INSERT INTO `kshop_country` VALUES (143, 'Montenegro');
INSERT INTO `kshop_country` VALUES (144, 'Montserrat');
INSERT INTO `kshop_country` VALUES (145, 'Morocco');
INSERT INTO `kshop_country` VALUES (146, 'Mozambique');
INSERT INTO `kshop_country` VALUES (147, 'Myanmar');
INSERT INTO `kshop_country` VALUES (148, 'Namibia');
INSERT INTO `kshop_country` VALUES (149, 'Nauru');
INSERT INTO `kshop_country` VALUES (150, 'Nepal');
INSERT INTO `kshop_country` VALUES (151, 'Netherlands Antilles');
INSERT INTO `kshop_country` VALUES (152, 'Netherlands');
INSERT INTO `kshop_country` VALUES (153, 'New Caledonia (French)');
INSERT INTO `kshop_country` VALUES (154, 'New Zealand');
INSERT INTO `kshop_country` VALUES (155, 'Nicaragua');
INSERT INTO `kshop_country` VALUES (156, 'Niger');
INSERT INTO `kshop_country` VALUES (157, 'Niue');
INSERT INTO `kshop_country` VALUES (158, 'Norfolk Island');
INSERT INTO `kshop_country` VALUES (159, 'North Korea');
INSERT INTO `kshop_country` VALUES (160, 'Northern Ireland');
INSERT INTO `kshop_country` VALUES (161, 'Norway');
INSERT INTO `kshop_country` VALUES (162, 'Oman');
INSERT INTO `kshop_country` VALUES (163, 'Pakistan');
INSERT INTO `kshop_country` VALUES (164, 'Panama');
INSERT INTO `kshop_country` VALUES (165, 'Papua New Guinea');
INSERT INTO `kshop_country` VALUES (166, 'Paraguay');
INSERT INTO `kshop_country` VALUES (167, 'Peru');
INSERT INTO `kshop_country` VALUES (168, 'Philippines');
INSERT INTO `kshop_country` VALUES (169, 'Pitcairn Island');
INSERT INTO `kshop_country` VALUES (170, 'Poland');
INSERT INTO `kshop_country` VALUES (171, 'Polynesia (French)');
INSERT INTO `kshop_country` VALUES (172, 'Portugal');
INSERT INTO `kshop_country` VALUES (173, 'Qatar');
INSERT INTO `kshop_country` VALUES (174, 'Reunion Island');
INSERT INTO `kshop_country` VALUES (175, 'Romania');
INSERT INTO `kshop_country` VALUES (176, 'Russia');
INSERT INTO `kshop_country` VALUES (177, 'Rwanda');
INSERT INTO `kshop_country` VALUES (178, 'S.Georgia Sandwich Isls');
INSERT INTO `kshop_country` VALUES (179, 'Sao Tome, Principe');
INSERT INTO `kshop_country` VALUES (180, 'San Marino');
INSERT INTO `kshop_country` VALUES (181, 'Saudi Arabia');
INSERT INTO `kshop_country` VALUES (182, 'Scotland');
INSERT INTO `kshop_country` VALUES (183, 'Senegal');
INSERT INTO `kshop_country` VALUES (184, 'Serbia');
INSERT INTO `kshop_country` VALUES (185, 'Seychelles');
INSERT INTO `kshop_country` VALUES (186, 'Shetland');
INSERT INTO `kshop_country` VALUES (187, 'Sierra Leone');
INSERT INTO `kshop_country` VALUES (188, 'Singapore');
INSERT INTO `kshop_country` VALUES (189, 'Slovak Republic');
INSERT INTO `kshop_country` VALUES (190, 'Slovenia');
INSERT INTO `kshop_country` VALUES (191, 'Solomon Islands');
INSERT INTO `kshop_country` VALUES (192, 'Somalia');
INSERT INTO `kshop_country` VALUES (193, 'South Africa');
INSERT INTO `kshop_country` VALUES (194, 'South Korea');
INSERT INTO `kshop_country` VALUES (195, 'Spain');
INSERT INTO `kshop_country` VALUES (196, 'Sri Lanka');
INSERT INTO `kshop_country` VALUES (197, 'St. Helena');
INSERT INTO `kshop_country` VALUES (198, 'St. Lucia');
INSERT INTO `kshop_country` VALUES (199, 'St. Pierre Miquelon');
INSERT INTO `kshop_country` VALUES (200, 'St. Martins');
INSERT INTO `kshop_country` VALUES (201, 'St. Kitts Nevis Anguilla');
INSERT INTO `kshop_country` VALUES (202, 'St. Vincent Grenadines');
INSERT INTO `kshop_country` VALUES (203, 'Sudan');
INSERT INTO `kshop_country` VALUES (204, 'Suriname');
INSERT INTO `kshop_country` VALUES (205, 'Svalbard Jan Mayen');
INSERT INTO `kshop_country` VALUES (206, 'Swaziland');
INSERT INTO `kshop_country` VALUES (207, 'Sweden');
INSERT INTO `kshop_country` VALUES (208, 'Switzerland');
INSERT INTO `kshop_country` VALUES (209, 'Syria');
INSERT INTO `kshop_country` VALUES (210, 'Tajikistan');
INSERT INTO `kshop_country` VALUES (211, 'Taiwan');
INSERT INTO `kshop_country` VALUES (212, 'Tahiti');
INSERT INTO `kshop_country` VALUES (213, 'Tanzania');
INSERT INTO `kshop_country` VALUES (214, 'Thailand');
INSERT INTO `kshop_country` VALUES (215, 'Togo');
INSERT INTO `kshop_country` VALUES (216, 'Tokelau');
INSERT INTO `kshop_country` VALUES (217, 'Tonga');
INSERT INTO `kshop_country` VALUES (218, 'Trinidad and Tobago');
INSERT INTO `kshop_country` VALUES (219, 'Tunisia');
INSERT INTO `kshop_country` VALUES (220, 'Turkmenistan');
INSERT INTO `kshop_country` VALUES (221, 'Turks and Caicos Isls');
INSERT INTO `kshop_country` VALUES (222, 'Tuvalu');
INSERT INTO `kshop_country` VALUES (223, 'Uganda');
INSERT INTO `kshop_country` VALUES (224, 'Ukraine');
INSERT INTO `kshop_country` VALUES (225, 'United Arab Emirates');
INSERT INTO `kshop_country` VALUES (226, 'Uruguay');
INSERT INTO `kshop_country` VALUES (227, 'Uzbekistan');
INSERT INTO `kshop_country` VALUES (228, 'Vanuatu');
INSERT INTO `kshop_country` VALUES (229, 'Vatican City State');
INSERT INTO `kshop_country` VALUES (230, 'Venezuela');
INSERT INTO `kshop_country` VALUES (231, 'Vietnam');
INSERT INTO `kshop_country` VALUES (232, 'Virgin Islands (Brit)');
INSERT INTO `kshop_country` VALUES (233, 'Wales');
INSERT INTO `kshop_country` VALUES (234, 'Wallis Futuna Islands');
INSERT INTO `kshop_country` VALUES (235, 'Western Sahara');
INSERT INTO `kshop_country` VALUES (236, 'Western Samoa');
INSERT INTO `kshop_country` VALUES (237, 'Yemen');
INSERT INTO `kshop_country` VALUES (238, 'Yugoslavia');
INSERT INTO `kshop_country` VALUES (239, 'Zaire');
INSERT INTO `kshop_country` VALUES (240, 'Zambia');
INSERT INTO `kshop_country` VALUES (241, 'Zimbabwe'); 
