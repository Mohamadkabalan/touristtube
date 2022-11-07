DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 245940;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 245940;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 245940;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 245940;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',245940,79,1),
('1',245940,130,1),
('Lobby',245940,1,2),
('1',245940,15,1),
('2',245940,16,2),
('3',245940,17,3),
('Coffee Shop',245940,8,3),
('1',245940,33,1),
('2',245940,34,2),
('3',245940,35,3),
('Gym',245940,3,4),
('1',245940,80,1),
('2',245940,81,2),
('3',245940,82,3),
('4',245940,83,4),
('Sauna',245940,211,5),
('1',245940,273,1),
('Swimming Pool',245940,2,6),
('1',245940,21,1),
('2',245940,92,2),
('3',245940,186,3),
('Classic Suite Room',245940,13,7),
('1',245940,66,1),
('2',245940,70,2),
('3',245940,71,3),
('4',245940,68,4),
('5',245940,67,5),
('Deluxe Suite',245940,374,8),
('1',245940,375,1),
('2',245940,377,2),
('3',245940,379,3),
('4',245940,376,4),
('5',245940,383,5),
('One Bedroom Apartment',245940,14,9),
('1',245940,75,1),
('2',245940,985,2),
('3',245940,73,3),
('4',245940,77,4),
('5',245940,76,5),
('6',245940,387,6),
('7',245940,74,7),
('Premier Suite',245940,517,10),
('1',245940,519,1),
('2',245940,520,2),
('3',245940,521,3),
('4',245940,524,4),
('5',245940,523,5),
('6',245940,516,6),
('7',245940,525,7);




INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 245940;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',245940,79,2,null,null,False,False,null),
(0,'thumb.jpg',245940,130,2,'{"scene": {"view": {"hlookat": "2.810","vlookat" : "0.311","fov" : "139.649"}}}',null,True,True,1),
(0,'thumb.jpg',245940,1,2,null,null,False,False,null),
(0,'thumb.jpg',245940,15,2,'{"scene": {"view": {"hlookat": "1.148","vlookat" : "0.000","fov" : "138.856"}}}',null,True,True,null),
(0,'thumb.jpg',245940,16,2,'{"scene": {"view": {"hlookat": "0.963","vlookat" : "0.000","fov" : "139.037"}}}',null,2,2,2),
(0,'thumb.jpg',245940,17,2,'{"scene": {"view": {"hlookat": "1.069","vlookat" : "0.000","fov" : "138.931"}}}',null,3,3,null),
(0,'thumb.jpg',245940,8,2,null,null,False,False,null),
(0,'thumb.jpg',245940,33,2,'{"scene": {"view": {"hlookat": "0.649","vlookat" : "0.000","fov" : "139.353"}}}',null,True,True,null),
(0,'thumb.jpg',245940,34,2,'{"scene": {"view": {"hlookat": "1.069","vlookat" : "0.000","fov" : "138.931"}}}',null,2,2,3),
(0,'thumb.jpg',245940,35,2,'{"scene": {"view": {"hlookat": "1.300","vlookat" : "0.000","fov" : "138.700"}}}',null,3,3,null),
(0,'thumb.jpg',245940,3,2,null,null,False,False,null),
(0,'thumb.jpg',245940,80,2,'{"scene": {"view": {"hlookat": "3.025","vlookat" : "0.431","fov" : "139.265"}}}',null,True,True,null),
(0,'thumb.jpg',245940,81,2,'{"scene": {"view": {"hlookat": "1.106","vlookat" : "0.000","fov" : "138.894"}}}',null,2,2,null),
(0,'thumb.jpg',245940,82,2,'{"scene": {"view": {"hlookat": "1.688","vlookat" : "0.000","fov" : "138.312"}}}',null,3,3,4),
(0,'thumb.jpg',245940,83,2,'{"scene": {"view": {"hlookat": "1.424","vlookat" : "0.000","fov" : "138.576"}}}',null,4,4,null),
(0,'thumb.jpg',245940,211,2,null,null,False,False,null),
(0,'thumb.jpg',245940,273,2,'{"scene": {"view": {"hlookat": "0.779","vlookat" : "0.000","fov" : "139.234"}}}',null,True,True,5),
(0,'thumb.jpg',245940,2,2,null,null,False,False,null),
(0,'thumb.jpg',245940,21,2,'{"scene": {"view": {"hlookat": "1.515","vlookat" : "0.000","fov" : "138.534"}}}',null,True,True,null),
(0,'thumb.jpg',245940,92,2,'{"scene": {"view": {"hlookat": "1.382","vlookat" : "0.000","fov" : "138.618"}}}',null,2,2,6),
(0,'thumb.jpg',245940,186,2,'{"scene": {"view": {"hlookat": "1.688","vlookat" : "0.000","fov" : "138.312"}}}',null,3,3,null),
(0,'thumb.jpg',245940,13,2,null,null,False,False,null),
(0,'thumb.jpg',245940,66,2,'{"scene": {"view": {"hlookat": "1.071","vlookat" : "0.000","fov" : "138.93"}}}',null,True,True,null),
(0,'thumb.jpg',245940,70,2,'{"scene": {"view": {"hlookat": "0.797","vlookat" : "0.000","fov" : "139.203"}}}',null,2,2,null),
(0,'thumb.jpg',245940,71,2,'{"scene": {"view": {"hlookat": "1.300","vlookat" : "0.000","fov" : "138.700"}}}',null,3,3,null),
(0,'thumb.jpg',245940,68,2,'{"scene": {"view": {"hlookat": "1.688","vlookat" : "0.000","fov" : "138.312"}}}',null,4,4,7),
(0,'thumb.jpg',245940,67,2,'{"scene": {"view": {"hlookat": "1.033","vlookat" : "0.000","fov" : "138.967"}}}',null,5,5,null),
(0,'thumb.jpg',245940,374,2,null,null,False,False,null),
(0,'thumb.jpg',245940,375,2,'{"scene": {"view": {"hlookat": "2.563","vlookat" : "0.000","fov" : "137.441"}}}',null,True,True,null),
(0,'thumb.jpg',245940,377,2,'{"scene": {"view": {"hlookat": "1.643","vlookat" : "0.000","fov" : "138.357"}}}',null,2,2,8),
(0,'thumb.jpg',245940,379,2,'{"scene": {"view": {"hlookat": "1.300","vlookat" : "0.000","fov" : "138.700"}}}',null,3,3,null),
(0,'thumb.jpg',245940,376,2,'{"scene": {"view": {"hlookat": "1.509","vlookat" : "0.000","fov" : "138.491"}}}',null,4,4,null),
(0,'thumb.jpg',245940,383,2,'{"scene": {"view": {"hlookat": "1.829","vlookat" : "0.000","fov" : "138.171"}}}',null,5,5,null),
(0,'thumb.jpg',245940,14,2,null,null,False,False,null),
(0,'thumb.jpg',245940,75,2,'{"scene": {"view": {"hlookat": "3.274","vlookat" : "0.000","fov" : "138.074"}}}',null,True,True,null),
(0,'thumb.jpg',245940,985,2,'{"scene": {"view": {"hlookat": "-45.182","vlookat" : "0.708","fov" : "138.779"}}}',null,2,2,9),
(0,'thumb.jpg',245940,73,2,'{"scene": {"view": {"hlookat": "1.509","vlookat" : "0.000","fov" : "138.491"}}}',null,3,3,null),
(0,'thumb.jpg',245940,77,2,'{"scene": {"view": {"hlookat": "4.709","vlookat" : "0.000","fov" : "139.037"}}}',null,4,4,10),
(0,'thumb.jpg',245940,76,2,'{"scene": {"view": {"hlookat": "-18.324","vlookat" : "0.785","fov" : "139.797"}}}',null,5,5,null),
(0,'thumb.jpg',245940,387,2,'{"scene": {"view": {"hlookat": "1.069","vlookat" : "0.000","fov" : "138.931"}}}',null,6,6,11),
(0,'thumb.jpg',245940,74,2,'{"scene": {"view": {"hlookat": "-2.595","vlookat" : "5.420","fov" : "138.534"}}}',null,7,7,null),
(0,'thumb.jpg',245940,517,2,null,null,False,False,null),
(0,'thumb.jpg',245940,519,2,'{"scene": {"view": {"hlookat": "-22.355","vlookat" : "1.012","fov" : "138.266"}}}',null,True,True,null),
(0,'thumb.jpg',245940,520,2,'{"scene": {"view": {"hlookat": "-18.867","vlookat" : "-0.000","fov" : "138.700"}}}',null,2,2,12),
(0,'thumb.jpg',245940,521,2,'{"scene": {"view": {"hlookat": "-15.455","vlookat" : "0.457","fov" : "138.074"}}}',null,3,3,null),
(0,'thumb.jpg',245940,524,2,'{"scene": {"view": {"hlookat": "-10.793","vlookat" : "0.057","fov" : "138.659"}}}',null,4,4,13),
(0,'thumb.jpg',245940,523,2,'{"scene": {"view": {"hlookat": "-5.731","vlookat" : "1.210","fov" : "139.171"}}}',null,5,5,null),
(0,'thumb.jpg',245940,516,2,'{"scene": {"view": {"hlookat": "-11.519","vlookat" : "2.173","fov" : "138.894"}}}',null,6,6,null),
(0,'thumb.jpg',245940,525,2,'{"scene": {"view": {"hlookat": "-9.175","vlookat" : "0.317","fov" : "138.025"}}}',null,7,7,14);





DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 245940
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 245940;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 245940);

