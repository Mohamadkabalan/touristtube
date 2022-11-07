#ADD NEW DIVISION
#
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (371, 'Residences Area', '23');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`) VALUES (399, 'Ambassador Suite', '15');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (400, 'Living Room - View 1', '15', '399');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (401, 'Living Room - View 2', '15', '399');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (402, 'Bedroom', '15', '399');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (403, 'Bathroom', '15', '399');
INSERT INTO `hotel_divisions` (`id`, `name`, `hotel_division_category_id`, `parent_id`) VALUES (404, 'Guest Bathroom', '15', '399');


#FIX DIVISION PARENT 
#
UPDATE `hotel_divisions` SET `parent_id`='369' WHERE `id`='371';


#ASSIGN DIVISIONS TO RODA AL MUROOJ HOTEL - 67011
#
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (790,'Main Entrance',67011,79,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (791,'View 1',67011,130,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (792,'Lobby',67011,1,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (793,'Reception',67011,15,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (794,'View 1',67011,16,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (795,'Pergolas',67011,28,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (796,'View 1',67011,29,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (797,'View 2',67011,30,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (798,'View 3',67011,31,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (799,'View 4',67011,32,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (800,'Terrace - View 1',67011,110,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (801,'Terrace - View 2',67011,111,6);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (802,'Double Decker',67011,167,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (803,'Entrance',67011,168,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (804,'Outdoor - View 1',67011,169,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (805,'Outdoor - View 2',67011,170,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (806,'Outdoor - View 3',67011,171,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (807,'Indoor - View 1',67011,197,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (808,'Indoor - View 2',67011,198,6);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (809,'Indoor - View 3',67011,199,7);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (810,'Indoor - View 4',67011,354,8);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (811,'Tabule',67011,173,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (812,'View 1',67011,174,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (813,'View 2',67011,175,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (814,'View 3',67011,176,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (815,'View 4',67011,177,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (816,'View 5',67011,178,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (817,'View 6',67011,179,6);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (818,'Flow Reception',67011,338,6);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (819,' - ',67011,339,0);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (820,'Flow Swimming Pool',67011,2,7);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (821,'View 1',67011,21,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (822,'View 2',67011,92,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (823,'View 3',67011,186,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (824,'View 4',67011,187,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (825,'Center of the Complex',67011,344,8);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (826,' - ',67011,345,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (827,'Residences Area',67011,369,9);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (828,'View 1',67011,372,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (829,'View 2',67011,373,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (830,'View 3',67011,371,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (831,'Classic Room',67011,36,10);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (832,'View 1',67011,37,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (833,'View 2',67011,38,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (834,'View 3',67011,39,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (835,'Bathroom',67011,245,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (836,'Deluxe Two Bedroom Suite',67011,374,11);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (837,'Living Room - View 1',67011,375,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (838,'Guest Bathroom',67011,376,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (839,'Kitchen',67011,377,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (840,'Bedroom 1 - View 1',67011,379,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (841,'Bedroom 2 - View 2',67011,380,6);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (842,'Bathroom',67011,383,7);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (843,'Bedroom 2',67011,384,8);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (844,'Bedroom 2 - Bathroom',67011,385,9);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (845,'Terrace',67011,386,10);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (846,'Presidential Suite',67011,355,12);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (847,'Salon - View 1',67011,356,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (848,'Kitchen',67011,357,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (849,'Guest Bathroom',67011,358,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (850,'Salon / Dining Room',67011,359,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (851,'Living Room / Salon',67011,360,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (852,'Bedroom 1 - View 1',67011,361,6);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (853,'Bedroom 1 - View 2',67011,362,7);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (854,'Bedroom 1 - Bathroom',67011,363,8);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (855,'Bedroom 2 - View 1',67011,364,9);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (856,'Bedroom 2 - View 2',67011,365,10);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (857,'Bedroom 2 - Bathroom',67011,366,11);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (858,'Terrace - View 1',67011,367,12);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (859,'Terrace - View 2',67011,368,13);


#3- ASSIGN ALLOWED CATEGORIES TO HOTEL AS PER ASSIGNED DIVISIONS
#
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 67011;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 67011;


#INSERT AL SARAB DEFAULT IMAGES
#
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 67011;

INSERT INTO `amadeus_hotel_image` (`user_id`, `filename`, `hotel_id`, `hotel_division_id`, `tt_media_type_id`, `location`, `default_pic`, `is_featured`)
VALUES ('0', 'thumb.jpg', '67011', '130'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '67011', '16'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '67011', '29'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '67011', '169'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '67011', '198'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '67011', '177'  , '2', '', '1' , '1')
	  ,('0', 'thumb.jpg', '67011', '21'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '67011', '345'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '67011', '371'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '67011', '37'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '67011', '379'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '67011', '386'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '67011', '356'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '67011', '367'  , '2', '', '1' , '1')
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
AND hhd.hotel_id = 67011
AND hd.id NOT IN (SELECT hi.hotel_division_id FROM amadeus_hotel_image hi where tt_media_type_id = 2 AND hi.hotel_id = hhd.hotel_id AND hi.hotel_division_id = hd.id)
);


#APPLY IMAGE CHANGES ON CMS_HOTEL TABLE
#
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 67011;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 67011);
