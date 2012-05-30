CREATE TABLE `contents` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `content` MEDIUMTEXT NOT NULL,
  `created` datetime DEFAULT NULL,
  `creator_id` mediumint(11) unsigned DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modifier_id` mediumint(11) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
