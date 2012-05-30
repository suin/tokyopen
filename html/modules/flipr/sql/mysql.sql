CREATE TABLE `album` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `cover_photo_id` int(11) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `photo` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `album_id` int(11) unsigned NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_ext` varchar(255) NOT NULL,
  `file_title` varchar(255) NOT NULL,
  `file_mime` varchar(255) NOT NULL,
  `file_size` int(11) unsigned NOT NULL,
  `file_width` int(11) unsigned NOT NULL,
  `file_height` int(11) unsigned NOT NULL,
  `view` int(11) unsigned NOT NULL,
  `created` datetime DEFAULT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `album_id` (`album_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `config` (`id`,`name`,`value`)
VALUES
	(1, 'thumb_width', '144'),
	(2, 'thumb_height', '144'),
	(3, 'max_width', '1024'),
	(4, 'max_height', '1024'),
	(5, 'max_file_size', '5242880');
