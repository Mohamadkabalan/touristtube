--
-- Table `tt_vendors`
--
INSERT INTO tt_vendors(id, name) VALUES ('3', 'HRS'), ('4', 'Amadeus');

--
-- Table `distance_poi_type_group`
--
CREATE TABLE IF NOT EXISTS `distance_poi_type_group` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `distance_poi_type_group` (`id`, `name`) VALUES
(1, 'landmark'),
(2, 'transportation');

--
-- Table `distance_poi_type`
--
CREATE TABLE IF NOT EXISTS `distance_poi_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(250) NOT NULL,
  `distance_poi_type_group_id` int(11) NOT NULL,
  `tt_vendor_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `distance_poi_type` (`id`, `name`, `distance_poi_type_group_id`, `tt_vendor_id`) VALUES
(1, 'Downtown', 1, 3),
(2, 'Airport', 2, 3),
(3, 'Train Station', 2, 3);

--
-- Table `hotel_poi`
--
CREATE TABLE IF NOT EXISTS `hotel_poi` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `hotel_id` int(11) NOT NULL,
  `name` varchar(250) NOT NULL,
  `distance_poi_type_id` int(11) NOT NULL,
  `distance` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

--
-- Add index for Table `hotel_poi`
--
ALTER TABLE `hotel_poi` ADD INDEX `FK_hotel_id` (`hotel_id` ASC);

--
-- Data for Table `hotel_poi`
--
INSERT INTO hotel_poi(hotel_id, name, distance_poi_type_id, distance)
	SELECT id, downtown, 1, distance_from_downtown FROM amadeus_hotel
	WHERE amadeus_hotel.downtown IS NOT NULL AND amadeus_hotel.distance_from_downtown IS NOT NULL;
		
INSERT INTO hotel_poi(hotel_id, name, distance_poi_type_id, distance)
	SELECT id, airport, 2, distance_from_airport FROM amadeus_hotel
	WHERE amadeus_hotel.airport IS NOT NULL AND amadeus_hotel.distance_from_airport IS NOT NULL;

INSERT INTO hotel_poi(hotel_id, name, distance_poi_type_id, distance)
	SELECT id, train_station, 3, distance_from_train_station FROM amadeus_hotel
	WHERE amadeus_hotel.train_station IS NOT NULL AND amadeus_hotel.distance_from_train_station IS NOT NULL;

--
-- Drop the 6 columns from Table `amadeus_hotel`
--
ALTER TABLE `amadeus_hotel` 
DROP `downtown`,
DROP `distance_from_downtown`,
DROP `airport`,
DROP `distance_from_airport`,
DROP `train_station`,
DROP `distance_from_train_station`;