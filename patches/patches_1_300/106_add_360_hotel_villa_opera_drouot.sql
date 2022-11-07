INSERT INTO `hotel_to_hotel_divisions` (`name`,`hotel_id`,`hotel_division_id`,`sort_order`) 
VALUES 
('Street View',20079,79,1),
('-',20079,130,1),
('Lobby',20079,1,2),
('-',20079,15,2),
('Breakfast',20079,6,3),
('View 1',20079,25,1),
('View 2',20079,26,2),
('Single Room',20079,530,4),
('View 1',20079,531,1),
('Bathroom',20079,535,2),
('Double or Twin Standard Room',20079,427,5),
('View 1',20079,428,1),
('View 2',20079,429,2),
('Bathroom',20079,430,3),
('Deluxe Room',20079,133,6),
('View 1',20079,134,1),
('View 2',20079,136,2),
('Bathroom',20079,138,3),
('View 3',20079,135,4),
('Junior suite',20079,212,7),
('View 1',20079,213,1),
('View 2',20079,214,2),
('Bathroom',20079,217,3);

DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 20079;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 20079;

DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 20079;


INSERT INTO `amadeus_hotel_image` (`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`) 
VALUES 
(0,'thumb.jpg',20079,130,2,'{"scene": {"view": {"hlookat": "-180.793", "vlookat": "-21.495"}}}','',1,1),
(0,'thumb.jpg',20079,15,2,'{"scene": {"view": {"hlookat": "0", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20079,25,2,'{"scene": {"view": {"hlookat": "-88.339", "vlookat": "2.113"}}}','',1,1),
(0,'thumb.jpg',20079,26,2,'{"scene": {"view": {"hlookat": "-4.352", "vlookat": "1.803"}}}','',1,1),
(0,'thumb.jpg',20079,531,2,'{"scene": {"view": {"hlookat": "0", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20079,535,2,'{"scene": {"view": {"hlookat": "-170.226", "vlookat": "8.298"}}}','',1,1),
(0,'thumb.jpg',20079,428,2,'{"scene": {"view": {"hlookat": "136.672", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20079,429,2,'{"scene": {"view": {"hlookat": "149.106", "vlookat": "12.969"}}}','',1,1),
(0,'thumb.jpg',20079,430,2,'{"scene": {"view": {"hlookat": "-217.866", "vlookat": "15.312"}}}','',1,1),
(0,'thumb.jpg',20079,134,2,'{"scene": {"view": {"hlookat": "350.143", "vlookat": "27.188"}}}','',1,1),
(0,'thumb.jpg',20079,136,2,'{"scene": {"view": {"hlookat": "118.578", "vlookat": "7.436"}}}','',1,1),
(0,'thumb.jpg',20079,138,2,'{"scene": {"view": {"hlookat": "-166.103", "vlookat": "6.752"}}}','',1,1),
(0,'thumb.jpg',20079,135,2,'{"scene": {"view": {"hlookat": "0", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20079,213,2,'{"scene": {"view": {"hlookat": "519.651", "vlookat": "7.5"}}}','',1,1),
(0,'thumb.jpg',20079,214,2,'{"scene": {"view": {"hlookat": "-332.042", "vlookat": "15.574"}}}','',1,1),
(0,'thumb.jpg',20079,217,2,'{"scene": {"view": {"hlookat": "-76.74", "vlookat": "16.928"}}}','',1,1);

DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 20079 
AND d.parent_id IS NULL;

DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 20079;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 20079);

