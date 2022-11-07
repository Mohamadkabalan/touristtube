#INSERT BROADWAY HOTEL DIVISIONS
#
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1365,'Main Entrance ',41322,79,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1366,'-',41322,130,0);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1367,'Lobby',41322,1,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1368,'Reception',41322,15,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1369,'View 1',41322,16,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1370,'Moulin Bleu',41322,28,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1371,'Buffet - View 1',41322,29,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1372,'Buffet - View 2',41322,30,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1373,'View 1',41322,31,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1374,'Entrance ',41322,32,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1375,'Club one ',41322,239,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1376,'View 1',41322,240,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1377,'View 2',41322,241,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1378,'Entrance ',41322,242,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1379,'Rai ',41322,167,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1380,'View 1',41322,168,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1381,'View 2',41322,169,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1382,'Entrance ',41322,170,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1383,'Thai Chi Spa',41322,4,6);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1384,'Reception',41322,102,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1385,'View 1',41322,103,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1386,'View 2',41322,104,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1387,'View 3',41322,105,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1388,'Double Room',41322,427,7);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1389,'View 1',41322,428,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1390,'View 2',41322,429,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1391,'Bathroom',41322,430,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1392,'Twin Room ',41322,246,8);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1393,'View 1',41322,247,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1394,'View 2',41322,248,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1395,'Bathroom',41322,249,3);


#3- ASSIGN ALLOWED CATEGORIES TO HOTEL AS PER ASSIGNED DIVISIONS
#
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 41322;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 41322;


INSERT INTO `amadeus_hotel_image` (`user_id`, `filename`, `hotel_id`, `hotel_division_id`, `tt_media_type_id`, `location`, `default_pic`, `is_featured`)
VALUES ('0', 'thumb.jpg', '41322', '130'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '41322', '16'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '41322', '29'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '41322', '32'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '41322', '241'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '41322', '168'  , '2', '', '1' , '1')
   	  ,('0', 'thumb.jpg', '41322', '170'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '41322', '102'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '41322', '103'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '41322', '428'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '41322', '429'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '41322', '247'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '41322', '248'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '41322', '249'  , '2', '', '1' , '1')
;


#6- INSERT SUB DIVISIONS TO HOTEL IMAGES TABLE
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
AND hhd.hotel_id = 41322
AND hd.id NOT IN (SELECT hi.hotel_division_id FROM amadeus_hotel_image hi where tt_media_type_id = 2 AND hi.hotel_id = hhd.hotel_id AND hi.hotel_division_id = hd.id)
);


#7- APPLY IMAGE CHANGES ON CMS_HOTEL TABLE
#
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 41322;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 41322);
