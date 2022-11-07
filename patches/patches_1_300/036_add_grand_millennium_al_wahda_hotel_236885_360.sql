#Fix SPA parent values
#
UPDATE `hotel_divisions` SET `parent_id`=NULL WHERE `id`='101';

UPDATE `hotel_divisions` SET `parent_id`='4' WHERE `id`='101';
UPDATE `hotel_divisions` SET `parent_id`='4' WHERE `id`='273';
UPDATE `hotel_divisions` SET `parent_id`='4' WHERE `id`='276';
UPDATE `hotel_divisions` SET `parent_id`='4' WHERE `id`='275';
UPDATE `hotel_divisions` SET `parent_id`='4' WHERE `id`='274';
UPDATE `hotel_divisions` SET `parent_id`='4' WHERE `id`='211';
UPDATE `hotel_divisions` SET `parent_id`='4' WHERE `id`='106';
UPDATE `hotel_divisions` SET `parent_id`='4' WHERE `id`='105';
UPDATE `hotel_divisions` SET `parent_id`='4' WHERE `id`='104';
UPDATE `hotel_divisions` SET `parent_id`='4' WHERE `id`='103';
UPDATE `hotel_divisions` SET `parent_id`='4' WHERE `id`='102';
UPDATE `hotel_divisions` SET `parent_id`='4' WHERE `id`='24';


#ADD GRAND MILLENNIUM AL WAHDA HOTEL DIVISIONS
#
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1266,'Entrance',236885,79,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1267,' -',236885,130,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1268,'Lobby',236885,1,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1269,' -',236885,15,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1270,'Copacabana Brazilian Churrascaria',236885,28,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1271,'Buffet 1',236885,29,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1272,'Buffet 2',236885,30,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1273,'Buffet 3',236885,31,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1274,'View 1',236885,32,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1275,'View 2',236885,110,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1276,'View 3',236885,111,6);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1277,'Entrance',236885,112,7);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1278,'Al Wahda All Day Dining',236885,167,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1279,'Buffet - View 1',236885,168,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1280,'Buffet - View 2',236885,169,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1281,'View 1',236885,170,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1282,'View 2',236885,171,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1283,'Prime Okryu Restaurant',236885,173,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1284,'Entrance',236885,174,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1285,'View 1',236885,175,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1286,'View 2',236885,176,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1287,'View 3',236885,177,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1288,'Tumbleweed Margarita Bar',236885,188,6);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1289,'Entrance',236885,189,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1290,'View 1',236885,190,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1291,'View 2',236885,191,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1292,'View 3',236885,192,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1293,'View 4',236885,461,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1294,'Sky Lounge Thirty One',236885,94,7);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1295,'Entrance',236885,95,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1296,'View 1',236885,96,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1297,'Level One Bar & Lounge',236885,435,8);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1298,'View 1',236885,436,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1299,'View 2',236885,437,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1300,'View 3',236885,438,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1301,'Porters English Pub',236885,7,9);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1302,'Entrance',236885,152,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1303,'View 1',236885,153,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1304,'View 2',236885,154,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1305,'View 3',236885,155,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1306,'View 4',236885,156,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1307,'Executive Club Lounge',236885,5,10);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1308,'Entrance',236885,321,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1309,'View 1',236885,322,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1310,'View 2',236885,433,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1311,'View 3',236885,434,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1312,'Atmosphere Cafe',236885,8,11);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1313,'View 1',236885,33,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1314,'View 2',236885,34,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1315,'Rooftop Swimming Pool',236885,2,12);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1316,'View 1',236885,21,0);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1317,'View 2',236885,92,0);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1318,'Al Wahda Health Club',236885,3,13);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1319,'Entrance',236885,80,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1320,'View 1',236885,81,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1321,'View 2',236885,82,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1322,'View 3',236885,83,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1323,'Zayna Spa',236885,4,14);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1324,'Entrance',236885,101,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1325,'Reception',236885,102,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1326,'Room 1',236885,273,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1327,'Couple Room',236885,24,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1328,'Couple Room - Bathroom',236885,103,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1329,'Deluxe Room',236885,36,15);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1330,'King Bed ',236885,37,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1331,'King Bed - Bathroom',236885,245,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1332,'Twin Bed',236885,447,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1333,'Twin Bed - Bathroom',236885,448,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1334,'Club Room',236885,40,16);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1335,'View 1',236885,41,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1336,'Bathroom',236885,335,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1337,'Junior Suite',236885,212,17);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1338,'View 1',236885,213,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1339,'Bathroom - View 1',236885,217,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1340,'Bathroom - View 2',236885,449,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1341,'Executive 1 Bedroom Apartment',236885,14,18);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1342,'Living Room / Bedroom',236885,75,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1343,'Bedroom',236885,73,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1344,'Bathroom',236885,74,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1345,'Living Room - View 1',236885,76,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1346,'Living Room / Kitchen',236885,78,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1347,'Guest Bathroom',236885,387,6);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1348,'Executive 2 Bedroom Apartment',236885,13,19);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1349,'Living Room',236885,66,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1350,'Bedroom 1',236885,67,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1351,'Bedroom 1 - Bathroom',236885,65,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1352,'Bedroom 2',236885,114,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1353,'Bedroom 2 - Bathroom',236885,69,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1354,'Guest Bathroom',236885,450,6);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1355,'Executive 3 Bedroom Apartment',236885,451,20);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1356,'Entrance / Guest Bathroom',236885,452,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1357,'Living Room',236885,453,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1358,'Kitchen',236885,454,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1359,'Bedroom 1',236885,455,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1360,'Bedroom 1 - Bathroom',236885,456,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1361,'Bedroom 2',236885,457,6);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1362,'Bedroom 2 - Bathroom',236885,458,7);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1363,'Bedroom 3',236885,459,8);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (1364,'Bedroom 3 - Bathroom',236885,460,9);


#3- ASSIGN ALLOWED CATEGORIES TO HOTEL AS PER ASSIGNED DIVISIONS
#
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 236885;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 236885;


#5- INSERT DEFAULT IMAGES
#
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 236885;
INSERT INTO `amadeus_hotel_image` (`user_id`, `filename`, `hotel_id`, `hotel_division_id`, `tt_media_type_id`, `location`, `default_pic`, `is_featured`)
VALUES ('0', 'thumb.jpg', '236885', '130'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '236885', '29'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '236885', '176'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '236885', '190'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '236885', '153'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '236885', '21'  , '2', '', '1' , '1')
   	  ,('0', 'thumb.jpg', '236885', '82'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '236885', '273'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '236885', '37'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '236885', '41'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '236885', '213'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '236885', '75'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '236885', '67'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '236885', '454'  , '2', '', '1' , '1')
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
AND hhd.hotel_id = 236885
AND hd.id NOT IN (SELECT hi.hotel_division_id FROM amadeus_hotel_image hi where tt_media_type_id = 2 AND hi.hotel_id = hhd.hotel_id AND hi.hotel_division_id = hd.id)
);


#7- APPLY IMAGE CHANGES ON CMS_HOTEL TABLE
#
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 236885;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 236885);
