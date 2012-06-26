CREATE TABLE `form` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL DEFAULT '',
  `mail_to_sender` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `mail_to_receiver` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `receiver_email` text NOT NULL,
  `header_description` mediumtext NOT NULL,
  `options` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `creator_id` mediumint(8) unsigned DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modifier_id` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `field` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `form_id` int(11) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `label` varchar(255) NOT NULL DEFAULT '',
  `type` varchar(100) NOT NULL DEFAULT '',
  `required` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `weight` smallint(3) unsigned NOT NULL DEFAULT '0',
  `description` mediumtext NOT NULL,
  `options` text NOT NULL,
  `created` datetime DEFAULT NULL,
  `creator_id` mediumint(8) unsigned DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  `modifier_id` mediumint(8) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_form_id_name` (`form_id`,`name`),
  KEY `ix_form_id` (`form_id`)
) ENGINE=InnoDB;