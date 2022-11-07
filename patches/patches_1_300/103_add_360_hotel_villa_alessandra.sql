INSERT INTO `hotel_to_hotel_divisions` (`name`,`hotel_id`,`hotel_division_id`,`sort_order`) \
VALUES 
('Street View ',20166,556,1),
('-',20166,557,1),
('Main Entrance',20166,79,2),
('-',20166,130,1),
('Lobby ',20166,1,3),
('-',20166,17,1),
('Breakfast',20166,6,4),
('-',20166,25,1),
('Terrace',20166,28,5),
('-',20166,29,1),
('Salon Bournois -Conference room',20166,131,6),
('-',20166,132,1),
('Single Room',20166,530,7),
('View1',20166,531,1),
('View2',20166,532,2),
('Standard Double - Twin Room',20166,246,8),
('View 1 ',20166,247,1),
('Bathroom',20166,251,2),
('Triple Room',20166,263,9),
('View 1',20166,264,1),
('View 2',20166,265,2),
('View 3',20166,266,3),
('Suite ',20166,159,10),
('Living Room',20166,160,1),
('Bedroom',20166,164,2),
('Bathroom ',20166,162,3);

DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 20166;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 20166;

DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 20166;

INSERT INTO `amadeus_hotel_image` (`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`) 
VALUES 
(0,'thumb.jpg',20166,557,2,'{"scene": {"view": {"hlookat": "355.519", "vlookat": "-4.597"}}}','',1,1),
(0,'thumb.jpg',20166,130,2,'{"scene": {"view": {"hlookat": "871.364", "vlookat": "0.103"}}}','',1,1),
(0,'thumb.jpg',20166,17,2,'{"scene": {"view": {"hlookat": "-95.864", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20166,25,2,'{"scene": {"view": {"hlookat": "262.361", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20166,29,2,'{"scene": {"view": {"hlookat": "328.569", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20166,132,2,'{"scene": {"view": {"hlookat": "772.226", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20166,531,2,'{"scene": {"view": {"hlookat": "153.002", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20166,532,2,'{"scene": {"view": {"hlookat": "-194.863", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20166,247,2,'{"scene": {"view": {"hlookat": "-31.435", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20166,251,2,'{"scene": {"view": {"hlookat": "212.863", "vlookat": "0"}}}','',0,0),
(0,'thumb.jpg',20166,264,2,'{"scene": {"view": {"hlookat": "-38.931", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20166,265,2,'{"scene": {"view": {"hlookat": "265.434", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20166,266,2,'{"scene": {"view": {"hlookat": "273.002", "vlookat": "0"}}}','',0,0),
(0,'thumb.jpg',20166,160,2,'{"scene": {"view": {"hlookat": "178.43", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20166,164,2,'{"scene": {"view": {"hlookat": "204.277", "vlookat": "0"}}}','',1,1),
(0,'thumb.jpg',20166,162,2,'{"scene": {"view": {"hlookat": "328.431", "vlookat": "0"}}}','',1,1);

DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 20166 
AND d.parent_id IS NULL;

DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 20166;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 20166);
