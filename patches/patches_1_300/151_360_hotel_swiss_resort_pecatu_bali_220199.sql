DELETE FROM hotel_to_hotel_divisions WHERE hotel_id = 220199;
DELETE FROM hotel_to_hotel_divisions_categories WHERE hotel_id = 220199;
DELETE FROM amadeus_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 220199;
DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2  AND hotel_id = 220199;


INSERT INTO `hotel_to_hotel_divisions`
(`name`,`hotel_id`,`hotel_division_id`,`sort_order`)
VALUES
('Entrance',220199,79,1),
('1',220199,130,1),
('2',220199,193,2),
('Lobby',220199,1,2),
('1',220199,15,1),
('2',220199,16,2),
('3',220199,17,3),
('Swiss Café Restaurant',220199,28,3),
('1',220199,29,1),
('2',220199,30,2),
('3',220199,31,3),
('4',220199,32,4),
('5',220199,110,5),
('6',220199,111,6),
('Cinnamon Bar',220199,184,4),
('1',220199,185,1),
('Private Romantic Dinner',220199,167,5),
('1',220199,168,1),
('Island Pool',220199,2,6),
('1',220199,21,1),
('2',220199,92,2),
('Gym',220199,3,7),
('1',220199,80,1),
('2',220199,81,2),
('Spa',220199,4,8),
('1',220199,101,1),
('Deluxe Room - King Bed',220199,568,9),
('1',220199,569,1),
('2',220199,570,2),
('3',220199,572,3),
('Deluxe Room - Twin Bed',220199,139,10),
('1',220199,140,1),
('2',220199,141,2),
('3',220199,143,3),
('Deluxe Room - King Bed - Balcony',220199,486,11),
('1',220199,487,1),
('2',220199,488,2),
('3',220199,489,3),
('4',220199,492,4),
('Deluxe Room - Twin Bed - Balcony',220199,626,12),
('1',220199,627,1),
('2',220199,628,2),
('3',220199,630,3);





INSERT INTO hotel_to_hotel_divisions_categories(hotel_division_category_id, hotel_id)
SELECT DISTINCT hd.hotel_division_category_id, hotel_id
FROM hotel_to_hotel_divisions hhd, hotel_divisions hd, hotel_divisions_categories hdc
WHERE 
hd.id = hhd.hotel_division_id
AND hd.hotel_division_category_id = hdc.id
AND hd.hotel_division_category_id NOT IN (SELECT hotel_division_category_id FROM hotel_to_hotel_divisions_categories hhdc WHERE hhdc.hotel_id = hhd.hotel_id)
AND hhd.hotel_id = 220199;




INSERT INTO `amadeus_hotel_image`
(`user_id`,`filename`,`hotel_id`,`hotel_division_id`,`tt_media_type_id`,`media_settings`,`location`,`default_pic`,`is_featured`,`sort_order`)
VALUES
(0,'thumb.jpg',220199,79,2,null,null,False,False,null),
(0,'thumb.jpg',220199,130,2,'{"scene": {"view": {"hlookat": "-12.407","vlookat" : "-0.627","fov" : "138.931"}}}',null,True,True,1),
(0,'thumb.jpg',220199,193,2,'{"scene": {"view": {"hlookat": "0.237","vlookat" : "-0.060","fov" : "138.779"}}}',null,False,False,2),
(0,'thumb.jpg',220199,1,2,null,null,False,False,null),
(0,'thumb.jpg',220199,15,2,'{"scene": {"view": {"hlookat": "2.856","vlookat" : "0.000","fov" : "137.150"}}}',null,True,True,1),
(0,'thumb.jpg',220199,16,2,'{"scene": {"view": {"hlookat": "1.382","vlookat" : "0.000","fov" : "138.618"}}}',null,False,False,2),
(0,'thumb.jpg',220199,17,2,'{"scene": {"view": {"hlookat": "1.424","vlookat" : "0.000","fov" : "138.576"}}}',null,False,False,3),
(0,'thumb.jpg',220199,28,2,null,null,False,False,null),
(0,'thumb.jpg',220199,29,2,'{"scene": {"view": {"hlookat": "108.873","vlookat" : "-0.886","fov" : "135.903"}}}',null,False,False,1),
(0,'thumb.jpg',220199,30,2,'{"scene": {"view": {"hlookat": "4.828","vlookat" : "0.340","fov" : "138.779"}}}',null,True,True,2),
(0,'thumb.jpg',220199,31,2,'{"scene": {"view": {"hlookat": "-1.026","vlookat" : "-0.106","fov" : "138.402"}}}',null,False,False,3),
(0,'thumb.jpg',220199,32,2,'{"scene": {"view": {"hlookat": "102.031","vlookat" : "0.132","fov" : "139.037"}}}',null,False,False,4),
(0,'thumb.jpg',220199,110,2,'{"scene": {"view": {"hlookat": "-1.790","vlookat" : "0.057","fov" : "138.659"}}}',null,False,False,5),
(0,'thumb.jpg',220199,111,2,'{"scene": {"view": {"hlookat": "1.466","vlookat" : "0.000","fov" : "138.534"}}}',null,True,True,6),
(0,'thumb.jpg',220199,184,2,null,null,False,False,null),
(0,'thumb.jpg',220199,185,2,'{"scene": {"view": {"hlookat": "-171.086","vlookat" : "-0.370","fov" : "137.268"}}}',null,True,True,1),
(0,'thumb.jpg',220199,167,2,null,null,False,False,null),
(0,'thumb.jpg',220199,168,2,'{"scene": {"view": {"hlookat": "-28.572","vlookat" : "0.000","fov" : "138.576"}}}',null,True,True,1),
(0,'thumb.jpg',220199,2,2,null,null,False,False,null),
(0,'thumb.jpg',220199,21,2,'{"scene": {"view": {"hlookat": "-2.515","vlookat" : "0.318","fov" : "139.20"}}}',null,True,True,1),
(0,'thumb.jpg',220199,92,2,'{"scene": {"view": {"hlookat": "181.240","vlookat" : "3.355","fov" : "138.534"}}}',null,False,False,2),
(0,'thumb.jpg',220199,3,2,null,null,False,False,null),
(0,'thumb.jpg',220199,80,2,'{"scene": {"view": {"hlookat": "-38.749","vlookat" : "1.858","fov" : "139.234"}}}',null,False,False,1),
(0,'thumb.jpg',220199,81,2,'{"scene": {"view": {"hlookat": "-63.698","vlookat" : "5.628","fov" : "138.740"}}}',null,True,True,2),
(0,'thumb.jpg',220199,4,2,null,null,False,False,null),
(0,'thumb.jpg',220199,101,2,'{"scene": {"view": {"hlookat": "125.329","vlookat" : "0.570","fov" : "138.219"}}}',null,True,True,1),
(0,'thumb.jpg',220199,568,2,null,null,False,False,null),
(0,'thumb.jpg',220199,569,2,'{"scene": {"view": {"hlookat": "1.704","vlookat" : "0.000","fov" : "138.402"}}}',null,True,True,1),
(0,'thumb.jpg',220199,570,2,'{"scene": {"view": {"hlookat": "1.598","vlookat" : "0.000","fov" : "138.402"}}}',null,False,False,2),
(0,'thumb.jpg',220199,572,2,'{"scene": {"view": {"hlookat": "1.341","vlookat" : "0.000","fov" : "138.659"}}}',null,False,False,3),
(0,'thumb.jpg',220199,139,2,null,null,False,False,null),
(0,'thumb.jpg',220199,140,2,'{"scene": {"view": {"hlookat": "0.894","vlookat" : "0.000","fov" : "139.106"}}}',null,False,False,1),
(0,'thumb.jpg',220199,141,2,'{"scene": {"view": {"hlookat": "1.069","vlookat" : "0.000","fov" : "138.931"}}}',null,True,True,2),
(0,'thumb.jpg',220199,143,2,'{"scene": {"view": {"hlookat": "1.069","vlookat" : "0.000","fov" : "138.931"}}}',null,False,False,3),
(0,'thumb.jpg',220199,486,2,null,null,False,False,null),
(0,'thumb.jpg',220199,487,2,'{"scene": {"view": {"hlookat": "141.036","vlookat" : "1.736","fov" : "138.402"}}}',null,True,True,1),
(0,'thumb.jpg',220199,488,2,'{"scene": {"view": {"hlookat": "-23.766","vlookat" : "0.072","fov" : "139.171"}}}',null,False,False,2),
(0,'thumb.jpg',220199,489,2,'{"scene": {"view": {"hlookat": "152.983","vlookat" : "1.685","fov" : "138.700"}}}',null,False,False,3),
(0,'thumb.jpg',220199,492,2,'{"scene": {"view": {"hlookat": "115.665","vlookat" : "3.007","fov" : "138.534"}}}',null,False,False,4),
(0,'thumb.jpg',220199,626,2,null,null,False,False,null),
(0,'thumb.jpg',220199,627,2,'{"scene": {"view": {"hlookat": "-5.238","vlookat" : "0.766","fov" : "138.700"}}}',null,True,True,1),
(0,'thumb.jpg',220199,628,2,'{"scene": {"view": {"hlookat": "-25.927","vlookat" : "1.008","fov" : "139.037"}}}',null,False,False,2),
(0,'thumb.jpg',220199,630,2,'{"scene": {"view": {"hlookat": "-31.526","vlookat" : "0.605","fov" : "138.074"}}}',null,False,False,3);




DELETE i
FROM amadeus_hotel_image i
INNER JOIN hotel_divisions d ON i.hotel_division_id = d.id
WHERE i.tt_media_type_id = 2 
AND i.hotel_id = 220199
AND d.parent_id IS NULL
;



DELETE FROM cms_hotel_image WHERE tt_media_type_id = 2 AND hotel_id = 220199;

INSERT INTO cms_hotel_image (user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, location, default_pic, is_featured, media_settings, sort_order)
(SELECT user_id, filename, hotel_id, hotel_division_id, tt_media_type_id, IF(location is null, "", location), default_pic, is_featured, media_settings, sort_order
FROM amadeus_hotel_image hi 
WHERE tt_media_type_id = 2 AND hi.hotel_id = 220199);

