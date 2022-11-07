INSERT INTO cms_hotel (id, name, description, address, street, district, zip_code, city, city_id, latitude, longitude, iso3_country_code, country_code, stars, logo, map_image, popularity, published, normalized_name, minimized_name, downtown, distance_from_downtown, airport, distance_from_airport, train_station,  distance_from_train_station, pre_20160901, g_submitted) VALUES ('417401', 'Hilton Dubai Al Habtoor City', NULL, NULL, 'Al Habtoor City - Sheikh Zayed Rd - Dubai - United Arab Emirates', NULL, NULL, 'Dubai', '1060078' , '25.184706', '55.255014', 'ARE', 'AE', '5', NULL, '', '1', '1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', '0');
INSERT INTO cms_hotel_source (hotel_id, source, source_id, source_code, location_id, location_code, trustyou_id) VALUES ('417401', 'hrs', '-1', NULL, '6879', NULL, NULL);

DELETE from cms_hotel_image where hotel_id not in (select id from cms_hotel);


ALTER TABLE `cms_hotel_source` 
ADD CONSTRAINT `fk_hotel_src_id`
  FOREIGN KEY (`hotel_id`)
  REFERENCES `cms_hotel` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;


ALTER TABLE `cms_hotel_image` 
ADD CONSTRAINT `fk_hotel_img_id`
  FOREIGN KEY (`hotel_id`)
  REFERENCES `cms_hotel` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;


ALTER TABLE `cms_hotel_image` 
ADD CONSTRAINT `fk_hotel_division`
  FOREIGN KEY (`hotel_division_id`)
  REFERENCES `hotel_divisions` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;



INSERT INTO `cms_hotel` (`id`,`name`,`description`,`address`,`street`,`district`,`zip_code`,`city`,`city_id`,`latitude`,`longitude`,`iso3_country_code`,`country_code`,`stars`,`logo`,`map_image`,`popularity`,`published`,`normalized_name`,`minimized_name`,`downtown`,`distance_from_downtown`,`airport`,`distance_from_airport`,`train_station`,`distance_from_train_station`,`pre_20160901`,`g_submitted`) VALUES (234822,'Byblos Hotel',NULL,NULL,'Tecom Area',NULL,'UAE','Dubai (Dubai)',1060078,25.090282,55.153770,'ARE','AE',4,NULL,'',1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0);
INSERT INTO cms_hotel_source(hotel_id, source, source_id, source_code, location_id, location_code, trustyou_id) VALUES ('234822', 'hrs', '460992', NULL, '6879', NULL, NULL);

INSERT INTO `cms_hotel` (`id`,`name`,`description`,`address`,`street`,`district`,`zip_code`,`city`,`city_id`,`latitude`,`longitude`,`iso3_country_code`,`country_code`,`stars`,`logo`,`map_image`,`popularity`,`published`,`normalized_name`,`minimized_name`,`downtown`,`distance_from_downtown`,`airport`,`distance_from_airport`,`train_station`,`distance_from_train_station`,`pre_20160901`,`g_submitted`) VALUES (236421,'Park Inn Gurgaon',NULL,NULL,'CIVIL LINES',NULL,'122001','Gurgaon (State of HaryĿna)',2100635,28.458110,77.030110,'IND','IN',4,NULL,'',1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0);
INSERT INTO cms_hotel_source(hotel_id, source, source_id, source_code, location_id, location_code, trustyou_id) VALUES ('236421', 'hrs', '444103', NULL, '10142', NULL, NULL);

INSERT INTO `cms_hotel` (`id`,`name`,`description`,`address`,`street`,`district`,`zip_code`,`city`,`city_id`,`latitude`,`longitude`,`iso3_country_code`,`country_code`,`stars`,`logo`,`map_image`,`popularity`,`published`,`normalized_name`,`minimized_name`,`downtown`,`distance_from_downtown`,`airport`,`distance_from_airport`,`train_station`,`distance_from_train_station`,`pre_20160901`,`g_submitted`) VALUES (236885,'Grand Millennium Al Wahda','Intended for both business and relaxation travel, Grand Millennium Al Wahda Abu Dhabi Hotel is preferably arranged in City Center; one of the city\'s most mainstream regions. The downtown area is only away and the airplane terminal can be come to inside 30 minutes. A sanctuary of rest and unwinding, the hotel will offer aggregate restoration just strides far from the city\'s various attractions, for example, The Kidz Factory Entertainment, Madinat Zayed Shopping Center, Sheik Khalifa Medical Pavalion. \r\n\r\nBasically, every one of the administrations and luxuries you have generally expected from Millennium and Copthorne Hotels are ideal in the solace of your own home. To give some examples of the hotel\'s offices, there are 24-hour room benefit, free Wi-Fi in all rooms, 24-hour security, day by day housekeeping, 24-hour front work area. \r\n\r\nFurthermore, all guestrooms highlight an assortment of solaces. Many rooms even give TV LCD/plasma screen, extra lavatory, restroom telephone, cloths, shoes to satisfy the most observing visitor. The hotel offers a fantastic assortment of recreational offices, including hot tub, wellness focus, sauna, open air pool, spa. When you are searching for agreeable and advantageous hotel in Abu Dhabi, make Grand Millennium Al Wahda Abu Dhabi Hotel your home far from home.',NULL,'Hazaa Bin Zayed Street',NULL,'107080','Abu Dhabi',1060174,24.469418,54.369621,'ARE','AE',5,NULL,'hotel/10023/11496/06104/9/hotel-100231-1496061049.png',1,1,'Grand Millennium Al Wahda','Millennium Wahda','Abu Dhabi  ',620,'Abu Dhabi International Airport (AUH)',28780,NULL,NULL,1,1);
INSERT INTO cms_hotel_source(hotel_id, source, source_id, source_code, location_id, location_code, trustyou_id) VALUES ('236885', 'hrs', '500278', NULL, '113', NULL, NULL);


INSERT INTO `cms_hotel` (`id`,`name`,`description`,`address`,`street`,`district`,`zip_code`,`city`,`city_id`,`latitude`,`longitude`,`iso3_country_code`,`country_code`,`stars`,`logo`,`map_image`,`popularity`,`published`,`normalized_name`,`minimized_name`,`downtown`,`distance_from_downtown`,`airport`,`distance_from_airport`,`train_station`,`distance_from_train_station`,`pre_20160901`,`g_submitted`) VALUES (357359,'The Muse Sarovar Portico Nehru Place',NULL,NULL,'A-1Chirag EnclaveNehru Place.',NULL,'110048','Delhi (National Capital Territory of Delhi)',2102463,28.547760,77.249250,'IND','IN',3,NULL,'',1,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,0,0);
INSERT INTO `cms_hotel_source` (`hotel_id`,`source`,`source_id`,`source_code`,`location_id`,`location_code`,`trustyou_id`) VALUES (357359,'hrs',417941,NULL,287553,NULL,NULL);


#UPDATE `cms_hotel` SET `id`='236421' WHERE `id`='358359';
#UPDATE `cms_hotel_source` SET `hotel_id`='236421' WHERE `hotel_id`='358359';

#UPDATE `cms_hotel` SET `id`='234822' WHERE `id`='359230';
#UPDATE `cms_hotel_source` SET `hotel_id`='234822' WHERE `hotel_id`='359230';

#UPDATE `cms_hotel` SET `id`='236885' WHERE `id`='100231';
#UPDATE `cms_hotel_source` SET `hotel_id`='236885' WHERE `hotel_id`='100231';



INSERT INTO `cms_hotel` 
(`id`,`name`,`address`,`street`,`district`,`zip_code`,`city`,`city_id`,`latitude`,`longitude`,`iso3_country_code`,`country_code`,`stars`,`logo`,`map_image`,`popularity`,`published`,`normalized_name`,`minimized_name`,`downtown`,`distance_from_downtown`,`airport`,`distance_from_airport`,`train_station`,`distance_from_train_station`,`pre_20160901`,`g_submitted`)
SELECT hotel_id, property_name, address_line_1, address_line_2, district, zip_code, w.name, h.city_id, h.latitude, h.longitude, c.iso3, w.country_code, h.stars, h.logo, COALESCE(h.map_image, ''), h.popularity, -2, h.property_name, h.property_name, null, null, null, null, null, null, 0, 0 
FROM 
hotel_to_hotel_divisions d, amadeus_hotel h, webgeocities w, cms_countries c
WHERE 
d.hotel_id = h.id
AND h.city_id = w.id
AND w.country_code = c.code
AND hotel_id not in (select id from cms_hotel)
GROUP BY hotel_id
;

ALTER TABLE hotel_to_hotel_divisions DROP FOREIGN KEY fk_hth_div_hotel_id;

ALTER TABLE hotel_to_hotel_divisions CHANGE hotel_id hotel_id INT(11) NOT NULL;

ALTER TABLE `hotel_to_hotel_divisions` 
ADD CONSTRAINT `fk_hth_div_hotelId`
  FOREIGN KEY (`hotel_id`)
  REFERENCES `cms_hotel` (`id`)
  ON DELETE RESTRICT
  ON UPDATE CASCADE;


ALTER TABLE `hotel_to_hotel_divisions_categories` 
DROP FOREIGN KEY `fk_hth_div_catg_hotel_id`;

ALTER TABLE `hotel_to_hotel_divisions_categories` 
CHANGE COLUMN `hotel_id` `hotel_id` INT(11) NOT NULL ,
DROP INDEX `fk_hth_div_catg_unique` ;

ALTER TABLE `hotel_to_hotel_divisions_categories` 
ADD CONSTRAINT `fk_hth_div_catg_hotel_id`
  FOREIGN KEY (`hotel_id`)
  REFERENCES `cms_hotel` (`id`)
  ON UPDATE CASCADE;


ALTER TABLE hotel_to_hotel_divisions_categories ADD CONSTRAINT UNIQUE KEY `fk_hth_div_catg_unique` (`hotel_id`,`hotel_division_category_id`);


UPDATE `cms_hotel` SET `published`='-1' WHERE `id`='194301';



