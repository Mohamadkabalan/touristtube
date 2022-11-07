INSERT INTO `hotel_to_hotel_divisions` (`name`,`hotel_id`,`hotel_division_id`,`sort_order`) 
VALUES 
('Street View',31995,556,1),
('View 1',31995,557,1),
('View 2',31995,558,2),
('View 3',31995,559,3),
('Lobby',31995,1,2),
('-',31995,15,1),
('Breakfast Room',31995,6,3),
('View 1',31995,25,1),
('View 2',31995,26,2),
('Single Room',31995,530,4),
('View 1',31995,531,1),
('Bathroom',31995,535,2),
('Double Standard Room',31995,427,5),
('-',31995,428,1),
('Twin Standard Room',31995,246,6),
('View 1',31995,247,1),
('View 2',31995,248,2),
('View 3 ',31995,249,3),
('View 4',31995,250,4),
('Double Superior Room',31995,546,7),
('View 1',31995,547,1),
('View 2',31995,548,2),
('Twin Superior Room',31995,139,8),
('View 1',31995,140,1),
('Bathroom',31995,143,2),
('Triple Room',31995,263,9),
('View 1',31995,264,1),
('View 2',31995,265,2),
('Bathroom',31995,266,3);

DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 31995;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 31995;

DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 31995;

INSERT INTO `amadeus_hotel_image` 
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`) 
VALUES 
(0,'thumb.jpg',31995,556,2,NULL,'',0,0),
(0,'thumb.jpg',31995,557,2,'{"scene": {"view": {"hlookat": "-163.467", "vlookat": "-22.518"}}}','',1,1),
(0,'thumb.jpg',31995,558,2,'{"scene": {"view": {"hlookat": "-140.574", "vlookat": "-16.519"}}}','',1,1),
(0,'thumb.jpg',31995,559,2,'{"scene": {"view": {"hlookat": "-158.021", "vlookat": "-10.555"}}}','',0,0),
(0,'thumb.jpg',31995,1,2,NULL,'',0,0),
(0,'thumb.jpg',31995,15,2,'{"scene": {"view": {"hlookat": "103.281", "vlookat": "0.396"}}}','',1,1),
(0,'thumb.jpg',31995,6,2,NULL,'',0,0),
(0,'thumb.jpg',31995,25,2,'{"scene": {"view": {"hlookat": "-537.514", "vlookat": "2.608"}}}','',1,1),
(0,'thumb.jpg',31995,26,2,'{"scene": {"view": {"hlookat": "-364.173", "vlookat": "0"}}}','',0,0),
(0,'thumb.jpg',31995,530,2,NULL,'',0,0),
(0,'thumb.jpg',31995,531,2,'{"scene": {"view": {"hlookat": "185.933", "vlookat": "15.068"}}}','',1,1),
(0,'thumb.jpg',31995,535,2,'{"scene": {"view": {"hlookat": "-179.769", "vlookat": "27.627"}}}','',1,1),
(0,'thumb.jpg',31995,427,2,NULL,'',0,0),
(0,'thumb.jpg',31995,428,2,'{"scene": {"view": {"hlookat": "-365.055", "vlookat": "20.601"}}}','',1,1),
(0,'thumb.jpg',31995,246,2,NULL,'',0,0),
(0,'thumb.jpg',31995,247,2,'{"scene": {"view": {"hlookat": "533.367", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',31995,248,2,'{"scene": {"view": {"hlookat": "378.916", "vlookat": "5.393"}}}','',1,1),
(0,'thumb.jpg',31995,249,2,'{"scene": {"view": {"hlookat": "-161.310", "vlookat": "16.078"}}}','',1,1),
(0,'thumb.jpg',31995,250,2,'{"scene": {"view": {"hlookat": "-78.810", "vlookat": "7.275"}}}','',1,1),
(0,'thumb.jpg',31995,546,2,NULL,'',0,0),
(0,'thumb.jpg',31995,547,2,'{"scene": {"view": {"hlookat": "-15.063", "vlookat": "4.500"}}}','',1,1),
(0,'thumb.jpg',31995,548,2,'{"scene": {"view": {"hlookat": "-233.802", "vlookat": "9.442"}}}','',1,1),
(0,'thumb.jpg',31995,139,2,NULL,'',0,0),
(0,'thumb.jpg',31995,140,2,'{"scene": {"view": {"hlookat": "-11.691", "vlookat": "9.078"}}}','',1,1),
(0,'thumb.jpg',31995,143,2,'{"scene": {"view": {"hlookat": "10.394", "vlookat": "2.994"}}}','',1,1),
(0,'thumb.jpg',31995,263,2,NULL,'',0,0),
(0,'thumb.jpg',31995,264,2,'{"scene": {"view": {"hlookat": "410.621", "vlookat": "15.804"}}}','',1,1),
(0,'thumb.jpg',31995,265,2,'{"scene": {"view": {"hlookat": "345.910", "vlookat": "24.644"}}}','',0,0),
(0,'thumb.jpg',31995,266,2,'{"scene": {"view": {"hlookat": "-9.087", "vlookat": "18.039"}}}','',1,1);

DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 31995 
AND d.parent_id IS NULL;

DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 31995;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 31995);


