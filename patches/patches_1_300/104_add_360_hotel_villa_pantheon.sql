INSERT INTO `hotel_to_hotel_divisions` (`name`,`hotel_id`,`hotel_division_id`,`sort_order`) 
VALUES 
('Street View',20962,556,1),
('-',20962,557,1),
('Lobby ',20962,1,2),
('View 1',20962,15,1),
('View 2',20962,16,2),
('The Tea House Bridge Bar',20962,7,3),
('-',20962,152,1),
('Terrace',20962,28,4),
('-',20962,29,1),
('Breakfast Room',20962,6,5),
('View 1',20962,25,1),
('View 2',20962,26,2),
('Entrance',20962,27,3),
('Double-Twin Standard Room',20962,427,6),
('View 1',20962,428,1),
('Bathroom',20962,429,2),
('Superior-Double Twin Room',20962,546,7),
('View 1',20962,547,1),
('Bathroom',20962,550,2),
('View 2',20962,548,3),
('View 3',20962,549,4),
('Deluxe Room',20962,374,8),
('View 1',20962,375,1),
('View 2',20962,379,2),
('View 3 ',20962,380,3),
('Bathroom',20962,383,4),
('Shower Room',20962,385,5),
('Duplexe',20962,573,9),
('View 1',20962,574,1),
('View 2',20962,575,2),
('Top Floor',20962,576,3);

DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 20962;

INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 20962;

DELETE FROM amadeus_hotel_image WHERE tt_media_type_id =2 AND hotel_id = 20962;


INSERT INTO `amadeus_hotel_image` (`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`) 
VALUES 
(0,'thumb.jpg',20962,557,2,'{"scene": {"view": {"hlookat": "28.673", "vlookat": "-21.873"}}}','',1,1),
(0,'thumb.jpg',20962,15,2,'{"scene": {"view": {"hlookat": "552.349", "vlookat": "1.186"}}}','',1,1),
(0,'thumb.jpg',20962,16,2,'{"scene": {"view": {"hlookat": "-500.637", "vlookat": "4.065"}}}','',1,1),
(0,'thumb.jpg',20962,152,2,'{"scene": {"view": {"hlookat": "-40.556", "vlookat": "4.912"}}}','',1,1),
(0,'thumb.jpg',20962,29,2,'{"scene": {"view": {"hlookat": "-296.001", "vlookat": "4.427"}}}','',0,0),
(0,'thumb.jpg',20962,25,2,'{"scene": {"view": {"hlookat": "-115.067", "vlookat": "21.050"}}}','',1,1),
(0,'thumb.jpg',20962,26,2,'{"scene": {"view": {"hlookat": "292.379", "vlookat": "6.246"}}}','',0,0),
(0,'thumb.jpg',20962,27,2,'{"scene": {"view": {"hlookat": "-110.985", "vlookat": "7.830"}}}','',0,0),
(0,'thumb.jpg',20962,428,2,'{"scene": {"view": {"hlookat": "-175.565", "vlookat": "20.906"}}}','',1,1),
(0,'thumb.jpg',20962,429,2,'{"scene": {"view": {"hlookat": "-1.110", "vlookat": "38.377"}}}','',1,1),
(0,'thumb.jpg',20962,547,2,'{"scene": {"view": {"hlookat": "-18.142", "vlookat": "9.154"}}}','',1,1),
(0,'thumb.jpg',20962,550,2,'{"scene": {"view": {"hlookat": "141.454", "vlookat": "14.868"}}}','',1,1),
(0,'thumb.jpg',20962,548,2,'{"scene": {"view": {"hlookat": "198.600", "vlookat": "-1.029"}}}','',1,1),
(0,'thumb.jpg',20962,549,2,'{"scene": {"view": {"hlookat": "-358.080", "vlookat": "-1.192"}}}','',1,1),
(0,'thumb.jpg',20962,375,2,'{"scene": {"view": {"hlookat": "-27.094", "vlookat": "1.704"}}}','',0,0),
(0,'thumb.jpg',20962,379,2,'{"scene": {"view": {"hlookat": "-30.003", "vlookat": "3.443"}}}','',1,1),
(0,'thumb.jpg',20962,380,2,'{"scene": {"view": {"hlookat": "531.878", "vlookat": "0.773"}}}','',1,1),
(0,'thumb.jpg',20962,383,2,'{"scene": {"view": {"hlookat": "-92.426", "vlookat": "12.418"}}}','',1,1),
(0,'thumb.jpg',20962,385,2,'{"scene": {"view": {"hlookat": "15.511", "vlookat": "10.215"}}}','',0,0),
(0,'thumb.jpg',20962,574,2,'{"scene": {"view": {"hlookat": "-379.432", "vlookat": "0.079"}}}','',1,1),
(0,'thumb.jpg',20962,575,2,'{"scene": {"view": {"hlookat": "165.768", "vlookat": "11.970"}}}','',1,1),
(0,'thumb.jpg',20962,576,2,'{"scene": {"view": {"hlookat": "166.967", "vlookat": "15.457"}}}','',1,1);

DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id =2 
AND i.hotel_id = 20962 
AND d.parent_id IS NULL;

DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 20962;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 20962);
