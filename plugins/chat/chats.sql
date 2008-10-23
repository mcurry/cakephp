CREATE TABLE  `chats` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `key` varchar(45) NOT NULL default '',
  `name` varchar(20) NOT NULL default '',
  `message` text NOT NULL,
  `ip_address` varchar(15) NOT NULL default '',
  `created` datetime default NULL,
  PRIMARY KEY  (`id`),
  KEY `KEY_IDX` (`key`)
);