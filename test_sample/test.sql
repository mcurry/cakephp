CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `created` datetime NOT NULL,
  `updated` datetime NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM;