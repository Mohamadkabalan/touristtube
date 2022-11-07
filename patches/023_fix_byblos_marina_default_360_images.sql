#INSERT BYBLOS MARINA DEFAULT IMAGES
#
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 237237;

INSERT INTO `amadeus_hotel_image` (`user_id`, `filename`, `hotel_id`, `hotel_division_id`, `tt_media_type_id`, `location`, `default_pic`, `is_featured`)
VALUES ('0', 'thumb.jpg', '237237', '130' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '237237', '16'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '237237', '30'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '237237', '175' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '237237', '198' , '2', '', '1' , '1')
	  ,('0', 'thumb.jpg', '237237', '191' , '2', '', '1' , '1')
	  ,('0', 'thumb.jpg', '237237', '96'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '237237', '21'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '237237', '101' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '237237', '80'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '237237', '140'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '237237', '134' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '237237', '145' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '237237', '214' , '2', '', '1' , '1')
;



#INSERT SUB DIVISIONS TO HOTEL IMAGES TABLE
#
INSERT INTO amadeus_hotel_image(user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, dupe_pool_id)
(
SELECT 0 AS 'userId', 'thumb.jpg' AS 'filename', hhd.hotel_id AS 'hotelId', hd.id AS 'divisionId', 2 AS 'mediaTypeId', '' AS 'location'
, 0 AS 'defaultPic'
, 0 AS 'isFeatured'
, 0 AS 'dupolId'

FROM hotel_to_hotel_divisions hhd, hotel_divisions hd
WHERE hhd.hotel_division_id = hd.id
AND hd.parent_id IS NOT NULL
AND hhd.hotel_id = 237237
AND hd.id NOT IN (SELECT hi.hotel_division_id FROM amadeus_hotel_image hi where tt_media_type_id = 2 AND hi.hotel_id = hhd.hotel_id AND hi.hotel_division_id = hd.id)
);



#APPLY IMAGE CHANGES ON CMS_HOTEL TABLE
#
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 237237;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 237237);

