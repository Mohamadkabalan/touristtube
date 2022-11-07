
#INSERT AL SARAB DEFAULT IMAGES
#
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 245382;

INSERT INTO `amadeus_hotel_image` (`user_id`, `filename`, `hotel_id`, `hotel_division_id`, `tt_media_type_id`, `location`, `default_pic`, `is_featured`)
VALUES ('0', 'thumb.jpg', '245382', '193' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '245382', '15'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '245382', '34'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '245382', '154' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '245382', '233' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '245382', '223' , '2', '', '1' , '1')
	  ,('0', 'thumb.jpg', '245382', '221'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '245382', '21' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '245382', '103'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '245382', '260' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '245382', '37' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '245382', '248' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '245382', '146' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '245382', '213'  , '2', '', '1' , '1')
;


#INSERT SUB DIVISIONS TO HOTEL IMAGES TABLE
#
INSERT INTO amadeus_hotel_image(user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, dupe_pool_id)
(
SELECT 0 AS 'userId', 'thumb.jpg' AS 'filename', hhd.hotel_id AS 'hotelId', hd.id AS 'divisionId', 2 AS 'mediaTypeId', '' AS 'location'
#, IF(hd.id = (select min(defHd.id) FROM hotel_divisions defHd WHERE defHd.parent_id = hd.parent_id GROUP BY defHd.parent_id) , 1, 0) AS 'defaultPic'
#, IF(hd.id = (select min(defHd.id) FROM hotel_divisions defHd WHERE defHd.parent_id = hd.parent_id GROUP BY defHd.parent_id) , 1, 0) AS 'isFeatured'
, 0 AS 'defaultPic'
, 0 AS 'isFeatured'
, 0 AS 'dupolId'

FROM hotel_to_hotel_divisions hhd, hotel_divisions hd
WHERE hhd.hotel_division_id = hd.id
AND hd.parent_id IS NOT NULL
AND hhd.hotel_id = 245382
AND hd.id NOT IN (SELECT hi.hotel_division_id FROM amadeus_hotel_image hi where tt_media_type_id = 2 AND hi.hotel_id = hhd.hotel_id AND hi.hotel_division_id = hd.id)
);


#APPLY IMAGE CHANGES ON CMS_HOTEL TABLE
#
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 245382;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 245382);


#Allow category Lounge to hotel
#
INSERT INTO `hotel_to_hotel_divisions_categories` (`hotel_id`, `hotel_division_category_id`) VALUES ('237237', '5');
