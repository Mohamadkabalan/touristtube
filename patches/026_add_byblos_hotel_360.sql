#Make sure hotel and Catg are unique keys
#
ALTER TABLE `hotel_to_hotel_divisions_categories` 
ADD UNIQUE INDEX `fk_hth_div_catg_unique` (`hotel_id` ASC, `hotel_division_category_id` ASC);


#ADD HOTEL BYBLOS DIVISIONS
#
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (733,'Lobby Area',234822,1,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (760,'Swimming Pool',234822,2,8);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (763,'Gym and Wellness',234822,3,9);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (766,'Thai Chi Spa',234822,4,10);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (755,'Crown and Lion',234822,7,7);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (738,'Murjan Coffee Shop',234822,8,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (734,'-',234822,15,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (735,'Reception / Concierge Desk',234822,16,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (736,'Lounge',234822,17,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (737,'Guest Relation Desk',234822,18,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (761,'View 1',234822,21,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (768,'Massage Room 1',234822,23,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (769,'Massage Room 2',234822,24,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (742,'Aldente Restaurant',234822,28,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (743,'View 1',234822,29,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (744,'View 2',234822,30,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (745,'View 3',234822,31,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (746,'View 4',234822,32,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (739,'View 1',234822,33,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (740,'View 2',234822,34,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (741,'View 3',234822,35,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (777,'Standard Room',234822,36,13);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (778,'-',234822,37,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (731,'Main Entrance',234822,79,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (764,'View 1',234822,80,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (765,'View 2',234822,81,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (762,'View 2',234822,92,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (767,'-',234822,101,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (770,'Massage Room 3',234822,102,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (732,'-',234822,130,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (772,'Conference Room',234822,131,11);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (773,'View 1',234822,132,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (783,'Executive Room',234822,144,15);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (784,'-',234822,145,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (785,'Bathroom',234822,148,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (756,'View 1',234822,152,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (757,'View 2',234822,153,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (758,'View 3',234822,154,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (759,'View 4',234822,155,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (786,'Cedar Suite',234822,159,16);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (788,'View 2',234822,160,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (789,'Bathroom',234822,162,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (787,'View 1',234822,164,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (752,'The Deck',234822,167,6);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (753,'View 1',234822,168,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (754,'View 2',234822,169,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (771,'Sauna',234822,211,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (747,'Chameleon Club',234822,239,5);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (748,'View 1',234822,240,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (749,'View 2 ',234822,241,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (750,'View 3',234822,242,3);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (751,'View 4',234822,243,4);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (779,'Bathroom',234822,245,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (780,'Twin Room',234822,246,14);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (781,'-',234822,247,1);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (782,'Bathroom',234822,251,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (774,'View 2',234822,252,2);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (775,'Buisness Center',234822,323,12);
INSERT INTO `hotel_to_hotel_divisions` (`id`,`name`,`hotel_id`,`hotel_division_id`,`sort_order`) VALUES (776,'-',234822,324,1);


#ASSIGN ALLOWED CATEGORIES TO HOTEL AS PER ASSIGNED DIVISIONS
#
INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hotel_id = 234822;



#INSERT BYBLOS HOTEL DEFAULT IMAGES
#
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 234822;

INSERT INTO `amadeus_hotel_image` (`user_id`, `filename`, `hotel_id`, `hotel_division_id`, `tt_media_type_id`, `location`, `default_pic`, `is_featured`)
VALUES ('0', 'thumb.jpg', '234822', '130' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '234822', '16'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '234822', '33'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '234822', '29' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '234822', '240' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '234822', '152' , '2', '', '1' , '1')
	  ,('0', 'thumb.jpg', '234822', '21'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '234822', '80' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '234822', '23'  , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '234822', '132' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '234822', '37' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '234822', '247' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '234822', '145' , '2', '', '1' , '1')
      ,('0', 'thumb.jpg', '234822', '164'  , '2', '', '1' , '1')
;


#APPLY IMAGE CHANGES ON CMS_HOTEL TABLE
#
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 234822;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 234822);

