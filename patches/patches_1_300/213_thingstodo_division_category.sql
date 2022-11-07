CREATE TABLE `thingstodo_division_category` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `thingstodo_division_category` (`id`, `name`) VALUES
(1, 'things to do');



CREATE TABLE `thingstodo_division` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL,
 `division_category_id` int(11) NOT NULL,
 `parent_id` int(11) DEFAULT NULL,
 `ttd_id` int(11) DEFAULT NULL,
 `media_settings` json DEFAULT NULL,
 `sort_order` int(11) NOT NULL DEFAULT '999',
 PRIMARY KEY (`id`),
 KEY `division_category_id` (`division_category_id`),
 KEY `parent_id` (`parent_id`),
 KEY `ttd_id` (`ttd_id`),
 KEY `sort_order` (`sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
