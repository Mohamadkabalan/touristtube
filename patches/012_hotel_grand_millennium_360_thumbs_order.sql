#Update Grand Millennium Hotel 360 thumbs order
#
UPDATE `amadeus_hotel_image` SET `default_pic` = '0',  `is_featured` = '0' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '25';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1',  `is_featured` = '1' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '27';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1',  `is_featured` = '1' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '24';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0',  `is_featured` = '0' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '23';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0',  `is_featured` = '0' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '33';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1',  `is_featured` = '1' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '35';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1',  `is_featured` = '1' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '152';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0',  `is_featured` = '0' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '44';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1',  `is_featured` = '1' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '45';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '47';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1',  `is_featured` = '1' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '48';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '73';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1',  `is_featured` = '1' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '75';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '65';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1',  `is_featured` = '1' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '66';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '52';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1',  `is_featured` = '1' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '53';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0',  `is_featured` = '0' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '29';
UPDATE `amadeus_hotel_image` SET `default_pic` = '0',  `is_featured` = '0' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '41';
UPDATE `amadeus_hotel_image` SET `default_pic` = '1',  `is_featured` = '1' WHERE `hotel_id` = '71637' AND `hotel_division_id` = '80';


#UPDATE 360 IMAGES OF CMS HOTEL TABLE
#
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 71637;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 71637);