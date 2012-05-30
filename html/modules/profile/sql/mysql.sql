CREATE TABLE `config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `property` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT '',
  `label` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  `required` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `option` text NOT NULL,
  `note` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `creator_id` mediumint(8) NOT NULL DEFAULT '0',
  `modified` datetime DEFAULT NULL,
  `modifier_id` mediumint(8) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`)
) ENGINE=InnoDB;

CREATE TABLE `set` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `creator_id` mediumint(8) NOT NULL,
  `modified` datetime DEFAULT NULL,
  `modifier_id` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_name` (`name`),
  KEY `ix_id_title` (`id`,`title`)
) ENGINE=InnoDB;

CREATE TABLE `set_property_link` (
  `set_id` int(11) unsigned NOT NULL,
  `property_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`set_id`,`property_id`)
) ENGINE=InnoDB;

INSERT INTO `set` (`id`, `name`, `title`, `description`, `created`, `creator_id`, `modified`, `modifier_id`)
VALUES (1, 'profile', 'プロフィール', '', NULL, 1, NULL, 1);
