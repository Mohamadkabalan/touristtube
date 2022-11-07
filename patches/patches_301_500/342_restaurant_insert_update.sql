DROP TABLE restaurant;

CREATE TABLE `restaurant` (
 `id` int(11) NOT NULL AUTO_INCREMENT,
 `name` varchar(255) NOT NULL,
 `description` text,
 `city_id` int(11) DEFAULT NULL,
 `country_code` char(2) DEFAULT NULL,
 `address` varchar(255) DEFAULT NULL,
 `latitude` double DEFAULT NULL,
 `longitude` double DEFAULT NULL,
 `hotel_id` int(11) DEFAULT NULL,
 `division_id` int(11) DEFAULT NULL,
 `is_featured` tinyint(1) NOT NULL DEFAULT '0',
 `sort_order` smallint(6) NOT NULL DEFAULT '1',
 `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
 `published` tinyint(1) NOT NULL DEFAULT '1',
 PRIMARY KEY (`id`),
 KEY `hotel_id` (`hotel_id`),
 KEY `division_id` (`division_id`),
 KEY `is_featured` (`is_featured`),
 KEY `sort_order` (`sort_order`),
 KEY `published` (`published`),
 KEY `city_id` (`city_id`),
 KEY `country_code` (`country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `restaurant`(`name`, `city_id`, `country_code`, `address`, `latitude`, `longitude`, `hotel_id`, `division_id`, `is_featured`, `sort_order`, `published`) 
SELECT hhd.name AS name, h.city_id, h.country_code, h.address, h.latitude, h.longitude, h.id AS hotel_id, hd.id AS division_id, 0 AS is_featured, 1 AS sort_order,1 AS published
FROM cms_hotel h 
INNER JOIN hotel_to_hotel_divisions hhd on (hhd.hotel_id = h.id) 
INNER JOIN hotel_divisions hd ON (hd.id = hhd.hotel_division_id AND hd.parent_id IS NULL) 
INNER JOIN hotel_divisions_categories hdc ON (hdc.id = hd.hotel_division_category_id) 
INNER JOIN hotel_divisions_categories_group hdcg ON (hdcg.id = hdc.hotel_division_category_group_id AND hdcg.id = 3) 
ORDER BY h.id;


# add non-existing entries
INSERT INTO `restaurant`(`name`, `city_id`, `country_code`, `address`, `latitude`, `longitude`, `hotel_id`, `division_id`, `is_featured`, `sort_order`, `published`) 
SELECT hhd.name AS name, h.city_id, h.country_code, h.address, h.latitude, h.longitude, h.id AS hotel_id, hd.id AS division_id, 0 AS is_featured, 1 AS sort_order, 1 AS published 
FROM cms_hotel h 
INNER JOIN hotel_to_hotel_divisions hhd on (hhd.hotel_id = h.id) 
INNER JOIN hotel_divisions hd ON (hd.id = hhd.hotel_division_id AND hd.parent_id IS NULL) 
INNER JOIN hotel_divisions_categories hdc ON (hdc.id = hd.hotel_division_category_id) 
INNER JOIN hotel_divisions_categories_group hdcg ON (hdcg.id = hdc.hotel_division_category_group_id AND hdcg.id = 3) 
WHERE NOT EXISTS (SELECT 1 FROM restaurant WHERE hotel_id = h.id AND name = hhd.name) 
ORDER BY h.id;
