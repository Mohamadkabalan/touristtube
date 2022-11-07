DELETE FROM cms_hotel_source WHERE source_id = -1 AND source = 'hrs';

DELETE FROM cms_hotel_source WHERE hotel_id = 248918 AND source = 'tt' AND source_id = -1;
DELETE FROM cms_hotel_source WHERE hotel_id = 244707 AND source = 'tt' AND source_id = -1;
DELETE FROM cms_hotel_source WHERE hotel_id = 231381 AND source = 'tt' AND source_id = -1;

UPDATE `cms_hotel_source` SET `location_id` = '6879' WHERE hotel_id = 287893;
UPDATE `cms_hotel_source` SET `location_id` = '6879' WHERE hotel_id = 248902;
UPDATE `cms_hotel_source` SET `location_id` = '6879' WHERE hotel_id = 245940;
UPDATE `cms_hotel_source` SET `location_id` = '6879' WHERE hotel_id = 245382;
UPDATE `cms_hotel_source` SET `location_id` = '6879' WHERE hotel_id = 223724;

DELETE FROM cms_hotel_city WHERE location_id = 1060078 AND city_id = 1060078 AND source = 'tt';
UPDATE cms_hotel_city SET city_name = 'Dubai' WHERE location_id = 6879 AND city_id = 1060078;

DELETE FROM cms_hotel_city WHERE location_id = 1918158 AND city_id = 1918158 AND source = 'tt';
UPDATE cms_hotel_city SET city_name = 'Denpasar' WHERE location_id = 89243 AND city_id = 1918158;
UPDATE cms_hotel_source SET location_id = 89243 WHERE hotel_id = 220199 AND source = 'tt';

DELETE FROM cms_hotel_city WHERE location_id = 2094206 AND city_id = 2094206 AND source = 'tt';
UPDATE cms_hotel_source SET location_id = 589250 WHERE hotel_id = 237677 AND source = 'tt';

UPDATE cms_hotel_city SET location_id = 1245672 WHERE city_id = 2441949 AND source = 'tt';
UPDATE cms_hotel_source SET location_id = 1245672 WHERE hotel_id = 261771 AND source = 'tt';