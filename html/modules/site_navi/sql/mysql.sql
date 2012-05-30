CREATE TABLE `route` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) unsigned NOT NULL DEFAULT '0',
  `id_path` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `weight` smallint(3) unsigned zerofill NOT NULL DEFAULT '000',
  `weight_path` varchar(255) NOT NULL,
  `level` smallint(3) unsigned NOT NULL DEFAULT '0',
  `children` smallint(5) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `module_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `content_id` varchar(255) NOT NULL DEFAULT '',
  `url` text,
  `private_flag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `invisible_in_menu_flag` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `created` datetime DEFAULT NULL,
  `creator_id` mediumint(8) unsigned DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modifier_id` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_content_id` (`content_id`),
  UNIQUE KEY `uk_path` (`id_path`),
  KEY `ix_parent_id_weight` (`parent_id`,`weight`)
) ENGINE=InnoDB;

CREATE TABLE `external` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL,
  `created` datetime DEFAULT NULL,
  `creator_id` mediumint(8) unsigned DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modifier_id` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `route` (`id`, `parent_id`, `id_path`, `title`, `weight`, `weight_path`, `level`, `children`, `type`, `module_id`, `content_id`, `url`, `created`, `creator_id`, `modified`, `modifier_id`)
VALUES
	(1, 0, '/1/', 'TOP', 001, '/001/', 1, 0, 0, 0, '/site_navi/top/', '', NULL, NULL, NULL, NULL);
