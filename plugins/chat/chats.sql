CREATE TABLE  `chats` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `key` varchar(45) NOT NULL default '',
  `handle` varchar(20) NOT NULL default '',
  `text` text NOT NULL,
  `ip_address` varchar(12) NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `KEY_IDX` (`key`)
);